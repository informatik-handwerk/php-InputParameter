<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use ihde\php\InputParameter\Lang\Type_String;

class InputParameter_Single_String
    extends InputParameter_Single
    implements Type_String {
    
    protected string $value;
    
    /**
     * @param string $name
     * @param string $value
     */
    protected function __construct(string $name, string $value) {
        parent::__construct($name, $value);
        $this->value = $value;
    }
    
    /**
     * @param        $name
     * @param string $string
     * @return InputParameter_Single_String
     */
    public static function instance_direct($name, string $string): self {
        $instance = new static($name, $string);
        return $instance;
    }
    
    /**
     * @implements Instantiable_KeyValue
     * @inheritDoc
     */
    public static function instance_keyValue($key, $value): self {
        $instance = new static($key, $value);
        return $instance;
    }
    
    /**
     * @inheritDoc
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        return $this->seed;
    }
    
    
}

