<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_Test.php");

abstract class InputParameter_SingleTest
    extends InputParameter_Test {
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testGetValue(string $value): void {
        $instance = (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $value);
        
        try {
            $instance->getValue();
        } catch (\Throwable $t) {
            self::fail();
        }
    }
    
    
}
