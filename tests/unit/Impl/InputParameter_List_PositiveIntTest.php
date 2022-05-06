<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_ListTest.php");

use ihde\php\InputParameter\Impl\InputParameter_List_PositiveInt;

class InputParameter_List_PositiveIntTest
    extends InputParameter_ListTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_List_PositiveInt::class;
    
    /**
     * @return \string[][]
     * @throws \Exception
     */
    public function provideInstantiationStrings(): array {
        
        return [
            "empty" => [static::buildList([]), 0],
            "empty faked" => [static::buildList(["", ""]), 0],
            "one item" => [static::buildList([1]), 1],
            "one item, faked other" => [static::buildList(["", 2, ""]), 1],
            "three items" => [static::buildList([1, 2, 3]), 3],
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
     * @return void
     * @throws \InvalidArgumentException
     */
    public function testMultiTypeDirectInstantiation(): void {
        $instance = InputParameter_List_PositiveInt::instance_direct(
            self::KEY,
            2,
            "5",
            "5..15",
            [null,12],
            [2,null],
            [2,12],
        );
    }
    
    
}
