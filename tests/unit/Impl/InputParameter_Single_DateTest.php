<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_SingleTest.php");

use ihde\php\InputParameter\Impl\InputParameter_Single_Date;

class InputParameter_Single_DateTest
    extends InputParameter_SingleTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_Single_Date::class;
    
    /**
     * @return \string[][]
     */
    public function provideInstantiationStrings(): array {
        return [
            "now" => ["now"],
            "timestamp native" => ["@0"],
            "timestamp natural" => ["0"],
            "date" => ["2022-12-31"],
            "datetime" => ["2022-12-31 23:59:02"],
        ];
    }
    
    
}

