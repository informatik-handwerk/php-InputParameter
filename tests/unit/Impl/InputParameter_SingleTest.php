<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_Single_Date;
use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\InputParameter_Single;

require_once("InputParameterTest.php");

abstract class InputParameter_SingleTest
    extends InputParameterTest {
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): InputParameter {
        /** @var InputParameter_Single $instance */
        $instance = parent::testInstantiate($value);
        
        $instance2 = (static::INPUT_PARAMETER_CLASS)::instance_direct(
            self::KEY,
            $instance->getValue()
        );
        self::assertInstanceOf(static::INPUT_PARAMETER_CLASS, $instance2);
        self::assertSame(self::KEY, $instance2->getName());
        
        self::assertSame($instance->getValue(), $instance2->getValue());
        
        return $instance;
    }
    
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
