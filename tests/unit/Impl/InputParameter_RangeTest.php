<?php

namespace ihde\php\InputParameter\codeception\Impl;

require_once("InputParameter_Test.php");

abstract class InputParameter_RangeTest
    extends InputParameter_Test {
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testGet(string $value): void {
        $instance = (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $value);
        
        try {
            $lower = null;
            $upper = null;
            
            if ($instance->hasLowerBound()) {
                $lower = $instance->getLowerBound();
            }
            if ($instance->hasUpperBound()) {
                $upper = $instance->getUpperBound();
            }
            
            self::assertFalse($lower === null && $upper === null);
            
            $lower = $lower ?? $upper;
            $upper = $upper ?? $lower;
            self::assertGreaterThanOrEqual($lower, $upper);
            
        } catch (\Throwable $t) {
            self::fail();
        }
    }
    
    
}

