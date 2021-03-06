<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Range;
use ihde\php\InputParameter\Lang\Instantiable_fromStrings;
use ihde\php\InputParameter\Lang\Type_PositiveInt;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_PositiveInt
    extends InputParameter_Range
    implements Type_PositiveInt {
    
    /**
     * @param string                                 $name
     * @param                                        $seed
     * @param InputParameter_Single_PositiveInt|null $lowerBound
     * @param InputParameter_Single_PositiveInt|null $upperBound
     * @throws \InvalidArgumentException
     */
    protected function __construct(
        string $name,
        $seed,
        ?InputParameter_Single_PositiveInt $lowerBound,
        ?InputParameter_Single_PositiveInt $upperBound
    ) {
        parent::__construct($name, $seed, $lowerBound, $upperBound);
    }
    
    /**
     * @param          $name
     * @param int|null $lowerBound
     * @param int|null $upperBound
     * @return InputParameter_Range_PositiveInt
     * @throws \InvalidArgumentException
     */
    public static function instance_direct(
        $name,
        ?int $lowerBound,
        ?int $upperBound
    ): InputParameter_Range_PositiveInt {
        $seed = [$lowerBound, $upperBound];
        
        $instanceLowerBound = ($lowerBound === null)
            ? null
            : InputParameter_Single_PositiveInt::instance_direct($name, $lowerBound);
        $instanceUpperBound = ($upperBound === null)
            ? null
            : InputParameter_Single_PositiveInt::instance_direct($name, $upperBound);
        
        $instance = new static($name, $seed, $instanceLowerBound, $instanceUpperBound);
        return $instance;
    }
    
    /**
     * @implements Instantiable_fromStrings
     * @inheritDoc
     * @throws \InvalidArgumentException
     */
    public static function instance_fromStrings(string $key, string $value): self {
        $range = StringParser::splitRange($value);
        [$rawLowerBound, $rawUpperBound] = $range;
        
        $lowerBound = ($rawLowerBound === null)
            ? null
            : InputParameter_Single_PositiveInt::instance_fromStrings($key, $rawLowerBound);
        $upperBound = ($rawUpperBound === null)
            ? null
            : InputParameter_Single_PositiveInt::instance_fromStrings($key, $rawUpperBound);
        
        $instance = new static($key, $value, $lowerBound, $upperBound);
        return $instance;
    }
    
    /**
     * @inheritDoc
     * @return int
     */
    public function getLowerBound(): int {
        return $this->lowerBound->getValue();
    }
    
    /**
     * @inheritDoc
     * @return int
     */
    public function getUpperBound(): int {
        return $this->upperBound->getValue();
    }
    
}

