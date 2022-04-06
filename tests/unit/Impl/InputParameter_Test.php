<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\InputParameter;

abstract class InputParameter_Test
    extends \Codeception\Test\Unit {
    
    /** @var string|InputParameter */
    public const INPUT_PARAMETER_CLASS = "";
    public const KEY = "key";
    
    /**
     * @var \ihde\php\InputParameter\codeception\UnitTester
     */
    protected $tester;
    
    protected function _before() {
    }
    
    protected function _after() {
    }
    
    /**
     * @return \string[][]
     */
    abstract public function provideInstantiationStrings(): array;
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): void {
        $instance = (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $value);
        self::assertInstanceOf(static::INPUT_PARAMETER_CLASS, $instance);
        self::assertSame(self::KEY, $instance->getName());
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): void {
        $instance = (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $value);
        self::assertSame($value, $instance->__toString());
    }
    
    
}
