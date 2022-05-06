<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\StaticAPI;

final class StringParser
    extends StaticAPI {
    
    public const SPLITTER_collection = " ";
    public const SPLITTER_list = ",";
    public const SPLITTER_range = ".."; //do not change without understanding the code.
    
    /**
     * @param string $input
     * @return void
     */
    protected static function assertUntrimmable(string $input): void  {
        $trimCharacters = self::SPLITTER_collection . self::SPLITTER_list;
        $trim = \trim($input, $trimCharacters);
        
        if ($trim !== $input)  {
            throw new \InvalidArgumentException("Surrounded by not allowed characters.");
        }
    }
    
    /**
     * @param string $input
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function parse_positiveInt(string $input): int {
        self::assertUntrimmable($input);
    
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
        self::assertUntrimmable($input);
    
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
    public static function containsCollection(string $input): bool {
        $posSeparatorCollection = \mb_strpos($input, self::SPLITTER_collection);
        return $posSeparatorCollection !== false;
    }
    
    /**
     * @param string $input
     * @return bool
     */
    public static function containsList(string $input): bool {
        if (self::containsCollection($input)) {
            return false;
        }
    
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
        if (false
            || self::containsCollection($input)
            || self::containsList($input)
            || self::containsRange($input)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param string $input
     * @return string[]
     */
    public static function splitCollection(string $input): array {
        $split = \explode(self::SPLITTER_collection, $input);
        $filtered = \array_filter($split, static fn(string $s) => $s !== "");
        
        return $filtered;
    }
    
    /**
     * @param string $input
     * @return string[]
     */
    public static function splitList(string $input): array {
        $split = \explode(self::SPLITTER_list, $input);
        $filtered = \array_filter($split, static fn(string $s) => $s !== "");
        
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
        $nulled = \array_map(static fn(string $s) => ($s === "") ? null : $s, $split);
        
        return $nulled;
    }
    
    
}

