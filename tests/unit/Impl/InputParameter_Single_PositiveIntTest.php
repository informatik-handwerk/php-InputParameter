<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_Single_PositiveInt;

class InputParameter_Single_PositiveIntTest
    extends InputParameter_SingleTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_Single_PositiveInt::class;
    
    /**
     * @return \string[][]
     * @throws \Exception
     */
    public function provideInstantiationStrings(): array {
        return [
            "xero" => ["0"],
            "one" => ["1"],
            "random" => [(string)\random_int(0, \PHP_INT_MAX)],
            "maxint" => [(string)\PHP_INT_MAX],
        ];
    }
    
    
}
