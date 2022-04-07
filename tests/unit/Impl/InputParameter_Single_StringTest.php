<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_SingleTest.php");

use ihde\php\InputParameter\Impl\InputParameter_Single_String;
use ihde\php\InputParameter\InputParameter;

class InputParameter_Single_StringTest
    extends InputParameter_SingleTest {
    
    /** @var string|InputParameter */
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
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): InputParameter {
        $instance = parent::testToStringStability($value);
        $instance2 = (static::INPUT_PARAMETER_CLASS)::instance_direct(self::KEY, $instance->getValue());
        
        self::assertSame($value, $instance2->__toString());
        
        return $instance;
    }
    
    
}
