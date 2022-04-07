<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_ListTest.php");

use ihde\php\InputParameter\Impl\InputParameter_List_PositiveInt;
use ihde\php\InputParameter\StringParser;

class InputParameter_List_PositiveIntTest
    extends InputParameter_ListTest {
    
    public const INPUT_PARAMETER_CLASS = InputParameter_List_PositiveInt::class;
    
    /**
     * @return \string[][]
     * @throws \Exception
     */
    public function provideInstantiationStrings(): array {
        $listOf = static function (array $items, bool $addRandomTrim = false) {
            if ($addRandomTrim) {
                $items = \array_map(
                    static function ($s): string {
                        $s = (string)$s;
                        if ($s !== "") {
                            $s = \str_pad($s, \mb_strlen($s) + \random_int(1, 3), " ", \STR_PAD_RIGHT);
                            $s = \str_pad($s, \mb_strlen($s) + \random_int(1, 3), " ", \STR_PAD_LEFT);
                        }
                        return $s;
                    },
                    $items
                );
            }
            
            return \implode(StringParser::SPLITTER_list, $items);
        };
        
        return [
            "empty" => [$listOf([]), 0],
            "empty faked" => [$listOf(["", ""]), 0],
            "one item" => [$listOf([1]), 1],
            "one item, faked other" => [$listOf(["", 2, ""]), 1],
            "three items" => [$listOf([1, 2, 3]), 3],
            "with range item" => [$listOf(["1..3", 4, 5]), 3],
            "four items, with padding" => [$listOf([1, 2, 3, 4], true), 4],
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
