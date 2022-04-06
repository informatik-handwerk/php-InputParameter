<?php
namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\Impl\InputParameter_List_Date;
use ihde\php\InputParameter\Impl\InputParameter_Range_Date;
use ihde\php\InputParameter\StringParser;

class InputParameter_List_DateTest extends \Codeception\Test\Unit
{
    
    public const KEY = "key";
    
    /**
     * @var \ihde\php\InputParameter\codeception\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }
    
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
            "one item" => [$listOf(["now"]), 1],
            "one item, faked other" => [$listOf(["", "now", ""]), 1],
            "three items" => [$listOf(["2022-01-01", "now", 0]), 3],
            "with range item" => [$listOf(["1..3", 4, 5]), 3],
            "four items, with padding" => [$listOf([1, 2, 3, 4], true), 4],
        ];
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): void {
        $instance = InputParameter_List_Date::instance_keyValue(self::KEY, $value);
        self::assertInstanceOf(InputParameter_List_Date::class, $instance);
        self::assertSame(self::KEY, $instance->getName());
    
        foreach ($instance->getList() as $item) {
            self::assertInstanceOf(InputParameter_Range_Date::class, $item);
        }
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testToStringStability(string $value): void {
        $instance = InputParameter_List_Date::instance_keyValue(self::KEY, $value);
        self::assertSame($value, $instance->__toString());
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @param int    $size
     * @return void
     * @throws \Exception
     */
    public function testSize(string $value, int $size): void {
        $instance = InputParameter_List_Date::instance_keyValue(self::KEY, $value);
        self::assertSame($size !== 0, $instance->hasItems());
        self::assertCount($size, $instance);
        self::assertCount($size, $instance->getList());
    }
}
