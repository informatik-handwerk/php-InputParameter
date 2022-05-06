<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use ihde\php\InputParameter\Lang\Type_InputParameter_Date;
use ihde\php\InputParameter\StringParser;

class InputParameter_Single_Date
    extends InputParameter_Single
    implements Type_InputParameter_Date {
    
    protected \DateTimeImmutable $value;
    
    /**
     * @param string             $name
     * @param                    $seed
     * @param \DateTimeImmutable $value
     */
    protected function __construct(string $name, $seed, \DateTimeImmutable $value) {
        parent::__construct($name, $seed);
        $this->value = $value;
    }
    
    /**
     * @param                    $name
     * @param \DateTimeImmutable $date
     * @return InputParameter_Single_Date
     */
    public static function instance_direct($name, \DateTimeImmutable $date): self {
        $instance = new static($name, $date, $date);
        return $instance;
    }
    
    /**
     * @implements Instantiable_KeyValue
     * @inheritDoc
     * @throws \InvalidArgumentException|\Exception
     */
    public static function instance_keyValue($key, $value): self {
        $valueAsDate = StringParser::parse_date($value);
        $instance = new static($key, $value, $valueAsDate);
        return $instance;
    }
    
    /**
     * @inheritDoc
     * @return \DateTimeImmutable
     */
    public function getValue(): \DateTimeImmutable {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        if (\is_string($this->seed)) {
            return $this->seed;
        }
        
        return "@" . $this->value->getTimestamp();
    }
    
    
}

