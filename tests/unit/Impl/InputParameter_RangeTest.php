<?php

namespace ihde\php\InputParameter\codeception\Impl;

use ihde\php\InputParameter\InputParameter_Range;
use ihde\php\InputParameter\StringParser;

require_once("InputParameter_Test.php");

abstract class InputParameter_RangeTest
    extends InputParameter_Test {
    
    /** @var string|InputParameter_Range */
    public const INPUT_PARAMETER_CLASS = "";
    
    /**
     * @dataProvider provideInstantiationStrings
     * @param string $value
     * @return void
     * @throws \Exception
     */
    public function testInstantiate(string $value): void {
        parent::testInstantiate($value);
        
        /** @var  $instance */
        $instance = (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $value);
        if (!$instance->hasLowerBound() || !$instance->hasUpperBound()) {
            return;
        }
        
        $lower = $instance->getLowerBound();
        $upper = $instance->getUpperBound();
        
        if ($lower == $upper) {
            return;
        }
        
        if (!StringParser::containsRange($value)) {
            return;
        }
        
        try {
            $range = StringParser::splitRange($value);
            $rangeInvalid = \array_reverse($range);
            $valueInvalid = \implode(StringParser::SPLITTER_range, $rangeInvalid);
            (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $valueInvalid);
            (static::INPUT_PARAMETER_CLASS)::instance_keyValue(self::KEY, $valueInvalid);
            self::fail("was supposed to throw");
            
        } catch (\InvalidArgumentException $iae) {
            //ok
        }
    }
    
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

