<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\InputParameter_List;

require_once("InputParameter_Test.php");

abstract class InputParameter_ListTest
    extends InputParameter_Test {
    
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

