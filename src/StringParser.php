<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\StaticAPI;

final class StringParser
    extends StaticAPI {
    
    public const SPLITTER_list = ",";
    public const SPLITTER_range = ".."; //do not change without understanding/testing the code.
    
    /**
     * @param string $input
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function parse_positiveInt(string $input): int {
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
     * @throws \InvalidArgumentException|\Exception
     */
    public static function parse_date(string $input): \DateTimeImmutable {
        $asInt = \filter_var($input, \FILTER_VALIDATE_INT);
        
        if (\is_int($asInt)) {
            $asDate = (new \DateTimeImmutable())->setTimestamp($asInt);
    
            // @codeCoverageIgnoreStart
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
            // @codeCoverageIgnoreEnd
        } else {
            if ($input[0] === "@") {
                //ensure we do not land in +0 time zone just because...
                $inputTrim = \substr_replace($input, "", 0, 1);
                $inputInt = \filter_var($inputTrim, \FILTER_VALIDATE_INT);
                $asDate = (new \DateTimeImmutable())->setTimestamp($inputInt);
                
            } else {
                $asDate = new \DateTimeImmutable($input);
            }
            
        }
        
        return $asDate;
    }
    
    /**
     * @param string $input
     * @return bool
     */
    public static function containsList(string $input): bool {
        $posSeparatorList = \mb_strpos($input, self::SPLITTER_list);
        return $posSeparatorList !== false;
    }
    
    /**
     * @param string $input
     * @return bool
     */
    public static function containsRange(string $input): bool {
        if (self::containsList($input)) {
            return false;
        }
        
        if ($input === self::SPLITTER_range) { //special case of `\count($parts) === 2`
            return false;
        }
        
        $parts = \explode(self::SPLITTER_range, $input);
        if (\count($parts) > 2) {
            return false;
        }
        
        /*
        //also possible
        $join = \implode("", $parts);
        if ($join === "") {
            return false;
        }
        */
        
        if ($parts[0] === $input) { //implied `\count($parts) === 1`
            return false;
        }
        
        $partStartSuffix = \mb_substr($parts[0], -1);
        $partEndPrefix = \mb_substr($parts[1] ?? "", 0, 1);
        if ($partStartSuffix === "." || $partEndPrefix === ".") {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param string $input
     * @return bool
     */
    public static function containsStandalone(string $input): bool {
        if (self::containsList($input) || self::containsRange($input)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param string $input
     * @return string[]
     */
    public static function splitList(string $input): array {
        $split = \explode(self::SPLITTER_list, $input);
        $trimmed = \array_map("trim", $split);
        $filtered = \array_filter($trimmed);
        
        return $filtered;
    }
    
    /**
     * @param string $input
     * @return string[]
     */
    public static function splitRange(string $input): array {
        assert(self::containsRange($input));
        
        $split = \explode(self::SPLITTER_range, $input);
        $split[1] = $split[1] ?? "";
        $trimmed = \array_map("trim", $split);
        $nulled = \array_map(static fn(string $s) => ($s === "") ? null : $s, $trimmed);
        
        return $nulled;
    }
    
    
    /**
     * @param string $input
     * @param bool   $midnight
     * @param string $intervalString
     * @return string
     * @throws \InvalidArgumentException|\Exception
     */
    public static function ensureIsDateRange(
        string $input,
        bool $midnight = true,
        string $intervalString = "P1D"
    ): string {
        if (self::containsRange($input)) {
            $split = self::splitRange($input);
            foreach ($split as &$item) {
                if ($item !== null) {
                    $item = self::parse_date($item)
                        ->getTimestamp();
                }
            }
            return \implode(self::SPLITTER_range, $split);
        }
        
        if (self::containsStandalone($input)) {
            $date = self::parse_date($input);
            
            $dateFrom = $midnight
                ? $date->setTime(0, 0, 0)
                : $date;
            $dateTo = $dateFrom->add(new \DateInterval($intervalString));
            
            $result = $dateFrom->getTimestamp() . self::SPLITTER_range . $dateTo->getTimestamp();
            
            assert(self::containsRange($result));
            return $result;
        }
        
        throw new \InvalidArgumentException("Could not convert to date range: '$input'");
    }
    
    
}

