<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_Single_String;

class InputParameter_Single_StringTest
    extends \Codeception\Test\Unit {
    
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
    public function testInstantiate(string $value): void {
        $instance = InputParameter_Single_String::instance_keyValue(self::KEY, $value);
        self::assertInstanceOf(InputParameter_Single_String::class, $instance);
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): void {
        $instance = InputParameter_Single_String::instance_keyValue(self::KEY, $value);
        self::assertSame($value, $instance->__toString());
    }
    
    
}

