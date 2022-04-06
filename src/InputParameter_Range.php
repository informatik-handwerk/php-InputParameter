<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

abstract class InputParameter_Range
    extends InputParameter {
    protected string $rawInput;
    protected ?string $rawLowerBound = null;
    protected ?string $rawUpperBound = null;
    
    /**
     * @param string $input examples: "20..30", "2021-05-01..", "..now"
     */
    public function __construct(string $name, string $input) {
        parent::__construct($name);
    
        $this->rawInput = $input;
        $range = StringParser::splitRange($input);
        [$this->rawLowerBound, $this->rawUpperBound] = $range;
    }
    
    /**
     * @return bool
     */
    public function hasLowerBound(): bool {
        return $this->rawLowerBound !== null;
    }
    
    /**
     * @return bool
     */
    public function hasUpperBound(): bool {
        return $this->rawUpperBound !== null;
    }
    
    /**
     * Fails if null, call ->has*Bound() before
     * @return mixed
     */
    abstract public function getLowerBound();
    
    /**
     * Fails if null, call ->has*Bound() before
     * @return mixed
     */
    abstract public function getUpperBound();
    
    /**
     * @return string
     */
    public function __toString(): string {
        if (isset($this->rawInput)) {
            return $this->rawInput;
        }
        
        $asString = \implode(
            StringParser::SPLITTER_range,
            [$this->rawLowerBound, $this->rawUpperBound]
        );
        return $asString;
    }
    
    
}

