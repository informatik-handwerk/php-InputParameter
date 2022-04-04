<?php

declare(strict_types=1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\StaticAPI;

final class StringParser
  extends StaticAPI
{
    public const SPLITTER_list = ",";
    public const SPLITTER_range = "..";

    /**
     * @param string $input
     * @return int
     */
    public static function parse_positiveInt(string $input): int
    {
        $asInt = \filter_var($input, \FILTER_VALIDATE_INT);

        if (!\is_int($asInt) || $asInt < 0) {
            throw new \InvalidArgumentException(
                \sprintf(
                    "Expecting positive integer here, got %s",
                    \var_export($input, true)
                )
            );
        }

        return $asInt;
    }

    /**
     * @param string $input
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public static function parse_date(string $input): \DateTimeImmutable
    {
        $asInt = \filter_var($input, \FILTER_VALIDATE_INT);

        if (\is_int($asInt)) {
            $asDate = (new \DateTimeImmutable())->setTimestamp($asInt);

            { //paranoia
                $asTimestamp = $asDate->getTimestamp();

                if ($asInt !== $asTimestamp || $input !== (string)$asTimestamp) {
                    throw new \InvalidArgumentException(
                        \sprintf(
                            "Transforming to timestamp was unstable: (string)%s ~> (int)%s ~> (timestamp)%s",
                            $input,
                            $asInt,
                            $asTimestamp
                        )
                    );
                }
            }
        } else {
            $asDate = new \DateTimeImmutable($input);
        }

        return $asDate;
    }

    /**
     * @param string $input
     * @return bool
     */
    public static function containsList(string $input): bool
    {
        $posSeparatorList = \mb_strpos($input, self::SPLITTER_list);
        return $posSeparatorList !== false;
    }

    /**
     * @param string $input
     * @return bool
     */
    public static function containsRange(string $input): bool
    {
        $posSeparatorList = \mb_strpos($input, self::SPLITTER_list);
        $posSeparatorRange = \mb_strpos($input, self::SPLITTER_range);

        return $posSeparatorList === false && $posSeparatorRange !== false;
    }

    /**
     * @param string $input
     * @return bool
     */
    public static function containsStandalone(string $input): bool
    {
        $posSeparatorList = \mb_strpos($input, self::SPLITTER_list);
        $posSeparatorRange = \mb_strpos($input, self::SPLITTER_range);

        return $posSeparatorList === false && $posSeparatorRange === false;
    }

    /**
     * @param string $input
     * @return string[]
     */
    public static function splitList(string $input): array
    {
        $split = \explode(self::SPLITTER_list, $input);
        $trimmed = \array_map("trim", $split);
        $filtered = \array_filter($trimmed);

        return $filtered;
    }

    /**
     * @param string $input
     * @return string[]
     */
    public static function splitRange(string $input): array
    {
        $split = \explode(self::SPLITTER_range, $input);
        $split[1] = $split[1] ?? "";

        if (\count($split) !== 2) {
            throw new \InvalidArgumentException(
                \sprintf(
                    "Unexpected split by '%s' resulting in %s!==2 segments.",
                    self::SPLITTER_range,
                    \count($split)
                )
            );
        }

        $trimmed = \array_map("trim", $split);
        $nulled = \array_map(static fn(string $s) => ($s === "") ? null : $s, $trimmed);

        return $nulled;
    }

    /**
     * @param string $input
     * @return string
     * @throws \Exception
     */
    public static function ensureIsDateRange(string $input): string {
        if (self::containsRange($input))  {
            return $input;
        }

        $date = self::parse_date($input);

        $dateFrom = $date->setTime(0, 0, 0);
        $dateTo = $dateFrom->add(new \DateInterval("P1D"));

        $result = $dateFrom->getTimestamp() . self::SPLITTER_range . $dateTo->getTimestamp();

        assert(self::containsRange($result));
        return $result;
    }


}

