<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_Test.php");

abstract class InputParameter_ListTest
    extends InputParameter_Test {
    
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
        self::assertCount($size, $instance);
        self::assertCount($size, $instance->getList());
    }
    
    
}

