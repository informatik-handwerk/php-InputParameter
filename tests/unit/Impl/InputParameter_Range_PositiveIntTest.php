<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_RangeTest.php");

use ihde\php\InputParameter\Impl\InputParameter_Range_PositiveInt;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_PositiveIntTest
    extends InputParameter_RangeTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_Range_PositiveInt::class;
    
    /**
     * @return \string[][]
     */
    public function provideInstantiationStrings(): array {
        return [
            "0" => ["0" . StringParser::SPLITTER_range . "1"],
            "max" => ["0" . StringParser::SPLITTER_range . \PHP_INT_MAX],
        ];
    }
    
    
}
