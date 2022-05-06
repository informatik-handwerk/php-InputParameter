<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_ListTest.php");

use ihde\php\InputParameter\Impl\InputParameter_List_Date;
use ihde\php\InputParameter\Impl\InputParameter_Range_Date;
use ihde\php\InputParameter\Impl\InputParameter_Single_Date;

class InputParameter_List_DateTest
    extends InputParameter_ListTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_List_Date::class;
    
    /**
     * @return \string[][]
     * @throws \Exception
     */
    public function provideInstantiationStrings(): array {
        return [
            "empty" => [static::buildList([]), 0],
            "empty faked" => [static::buildList(["", ""]), 0],
            "one item" => [static::buildList(["now"]), 1],
            "one item, faked other" => [static::buildList(["", "now", ""]), 1],
            "three items" => [static::buildList(["2022-01-01", "now", 0]), 3],
            "with range item" => [static::buildList(["1..3", 4, 5]), 3],
        ];
    }
    
    /**
     * @return \string[][]
     * @throws \Exception
     */
    public function provideInvalidStrings(): array {
        return [
            "four items, with padding" => [static::buildList([1, 2, 3, 4], true), 4],
        ];
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): InputParameter_List_Date {
        $instance = parent::testInstantiate($value);
        
        foreach ($instance->getList() as $item) {
            self::assertInstanceOf(InputParameter_Range_Date::class, $item);
        }
        
        return $instance;
    }
    
    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    public function testMultiTypeDirectInstantiation(): void {
        $instance = InputParameter_List_Date::instance_direct(
            self::KEY,
            "now",
            0,
            new \DateTimeImmutable(),
            [null, "now"],
            ["0", new \DateTimeImmutable()],
            [0, 10],
            InputParameter_Single_Date::instance_keyValue(self::KEY, "now"),
            InputParameter_Range_Date::instance_keyValue(self::KEY, "..now"),
            [null, "15"],
            ["5", null],
            ["5", "15"],
        );
    }
    
    
}
