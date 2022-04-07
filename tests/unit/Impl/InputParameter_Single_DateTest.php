<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_SingleTest.php");

use ihde\php\InputParameter\Impl\InputParameter_Single_Date;
use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\StringParser;

class InputParameter_Single_DateTest
    extends InputParameter_SingleTest {
    
    /** @var string|InputParameter */
    public const INPUT_PARAMETER_CLASS = InputParameter_Single_Date::class;
    
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
    public function testToStringStability(string $value): InputParameter {
        $instance = parent::testToStringStability($value);
        $instance2 = (static::INPUT_PARAMETER_CLASS)::instance_direct(self::KEY, $instance->getValue());
    
        if ($value !== "now")  {
            $dateTimeImmutable1 = StringParser::parse_date($instance->__toString());
            $dateTimeImmutable2 = StringParser::parse_date($instance2->__toString());
            self::assertEquals($dateTimeImmutable1, $dateTimeImmutable2);
        }
        
        return $instance;
    }
    
    
}

