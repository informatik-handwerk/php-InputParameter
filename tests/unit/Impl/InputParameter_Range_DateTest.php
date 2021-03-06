<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_RangeTest.php");

use ihde\php\InputParameter\Impl\InputParameter_Range_Date;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_DateTest
    extends InputParameter_RangeTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_Range_Date::class;
    
    /**
     * @return \string[][]
     */
    public function provideInstantiationStrings(): array {
        return [
            "0" => ["0"],
            "after 0" => ["0" . StringParser::SPLITTER_range],
            "until now" => [StringParser::SPLITTER_range. "now"],
            "0 to now" => ["0" . StringParser::SPLITTER_range . "now"],
        ];
    }
    
    
}

