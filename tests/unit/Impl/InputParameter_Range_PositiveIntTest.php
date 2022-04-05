<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_Range_PositiveInt;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_PositiveIntTest
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
            "0" => ["0" . StringParser::SPLITTER_range . "1"],
            "max" => ["0" . StringParser::SPLITTER_range . \PHP_INT_MAX],
        ];
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): void {
        $instance = InputParameter_Range_PositiveInt::instance_keyValue(self::KEY, $value);
        self::assertInstanceOf(InputParameter_Range_PositiveInt::class, $instance);
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): void {
        $instance = InputParameter_Range_PositiveInt::instance_keyValue(self::KEY, $value);
        self::assertSame($value, $instance->__toString());
    }
    
    
}
