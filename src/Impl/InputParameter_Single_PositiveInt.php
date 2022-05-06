<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use ihde\php\InputParameter\Lang\Type_InputParameter_PositiveInt;
use ihde\php\InputParameter\StringParser;

class InputParameter_Single_PositiveInt
    extends InputParameter_Single
    implements Type_InputParameter_PositiveInt {
    
    protected int $value;
    
    /**
     * @param string $name
     * @param        $seed
     * @param int    $value
     */
    protected function __construct(string $name, $seed, int $value) {
        parent::__construct($name, $seed);
        $this->value = $value;
    }
    
    /**
     * @param     $name
     * @param int $int
     * @return InputParameter_Single_PositiveInt
     */
    public static function instance_direct($name, int $int): self {
        $instance = new static($name, $int, $int);
        return $instance;
    }
    
    /**
     * @implements Instantiable_KeyValue
     * @inheritDoc
     * @throws \InvalidArgumentException
     */
    public static function instance_keyValue($key, $value): self {
        $valueAsInt = StringParser::parse_positiveInt($value);
        $instance = new static($key, $value, $valueAsInt);
        return $instance;
    }
    
    /**
     * @inheritDoc
     * @return int
     */
    public function getValue(): int {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        if (\is_string($this->seed)) {
            return $this->seed;
        }
        
        return (string)$this->value;
    }
    
    
}

