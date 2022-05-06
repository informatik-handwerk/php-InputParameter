<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\InputParameter_List;
use ihde\php\InputParameter\StringParser;

require_once("InputParameter_Test.php");

abstract class InputParameter_ListTest
    extends InputParameter_Test {
    
    /**
     * @param array $items
     * @param bool  $addRandomTrim
     * @return string
     * @throws \Exception
     */
    protected static function buildList(array $items, bool $addRandomTrim = false) {
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
    }

    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): InputParameter_List {
        /** @var InputParameter_List $instance */
        $instance = parent::testInstantiate($value);
        
        $instance2 = (static::INPUT_PARAMETER_CLASS)::instance_direct(
            self::KEY,
            ...$instance->getList()
        );
        
        self::assertInstanceOf(static::INPUT_PARAMETER_CLASS, $instance2);
        self::assertSame(self::KEY, $instance2->getName());
        
        $list = $instance->getList();
        $list2 = $instance2->getList();
        self::assertSame($list, $list2);
        
        return $instance;
    }
    
    /**
     * @dataProvider provideInvalidStrings
     * @param string $value
     * @return void
     */
    public function testInvalidInstantiateString(string $value): void {
        try {
            parent::testInstantiate($value);
        self::fail();
        } catch (\Throwable $t)  {
            //ok
        }
    }
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @param int    $size
     * @return void
     * @throws \Exception
     */
    public function testSize(string $value, int $size): void {
        $instance = (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $value);
        self::assertSame($size !== 0, $instance->hasItems());
        //self::assertCount($size, $instance);
        self::assertCount($size, $instance->getList());
    }
    
    
}

