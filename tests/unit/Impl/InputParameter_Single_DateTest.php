<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_Single_Date;

class InputParameter_Single_DateTest
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
            "now" => ["now"],
            "timestamp native" => ["@0"],
            "timestamp natural" => ["0"],
            "date" => ["2022-12-31"],
            "datetime" => ["2022-12-31 23:59:02"],
        ];
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): void {
        $instance = InputParameter_Single_Date::instance_keyValue(self::KEY, $value);
        self::assertInstanceOf(InputParameter_Single_Date::class, $instance);
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): void {
        $instance = InputParameter_Single_Date::instance_keyValue(self::KEY, $value);
        self::assertSame($value, $instance->__toString());
    }
    
    
}

