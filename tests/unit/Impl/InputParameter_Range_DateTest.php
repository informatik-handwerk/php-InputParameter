<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_Range_Date;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_DateTest
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
            "after 0" => ["0" . StringParser::SPLITTER_range],
            "until now" => ["0" . StringParser::SPLITTER_range],
            "0 to now" => ["0" . StringParser::SPLITTER_range . "now"],
        ];
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): void {
        $instance = InputParameter_Range_Date::instance_keyValue(self::KEY, $value);
        self::assertInstanceOf(InputParameter_Range_Date::class, $instance);
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): void {
        $instance = InputParameter_Range_Date::instance_keyValue(self::KEY, $value);
        self::assertSame($value, $instance->__toString());
    }
    
    
}

