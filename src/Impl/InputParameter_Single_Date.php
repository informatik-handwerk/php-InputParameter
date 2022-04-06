<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;
use ihde\php\InputParameter\StringParser;

class InputParameter_Single_Date
    extends InputParameter_Single {
    
    protected string $value;
    protected \DateTimeImmutable $valueTyped;
    
    /**
     * @param string $name
     * @param string $input
     * @throws \Exception
     */
    public function __construct(string $name, string $input) {
        parent::__construct($name);
        $this->value = $input;
        $this->valueTyped = StringParser::parse_date($this->value);
    }
    
    /**
     * @return \DateTimeImmutable
     */
    public function getValue(): \DateTimeImmutable {
        return $this->valueTyped;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        return $this->value;
    }
    
    
}

