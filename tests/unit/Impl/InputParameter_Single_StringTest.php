<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_SingleTest.php");

use ihde\php\InputParameter\Impl\InputParameter_Single_String;

class InputParameter_Single_StringTest
    extends InputParameter_SingleTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_Single_String::class;
    
    /**
     * @return \string[][]
     */
    public function provideInstantiationStrings(): array {
        return [
            "void" => [""],
            "value" => ["value"],
        ];
    }
    
    
}
