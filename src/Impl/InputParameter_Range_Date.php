<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Range;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_Date
    extends InputParameter_Range {
    protected ?InputParameter_Single_Date $lowerBoundParam = null;
    protected ?InputParameter_Single_Date $upperBoundParam = null;
    
    /**
     * @throws \Exception
     */
    public function __construct(string $name, string $input) {
        $inputAsRange = StringParser::ensureIsDateRange($input);
        
        parent::__construct($name, $inputAsRange);
        $this->rawInput = $input;
        
        $this->_validate();
        
        if ($this->hasLowerBound()) {
            $this->lowerBoundParam = new InputParameter_Single_Date($name, $this->rawLowerBound);
        }
        if ($this->hasUpperBound()) {
            $this->upperBoundParam = new InputParameter_Single_Date($name, $this->rawUpperBound);
        }
    }
    
    /**
     * @throws \InvalidArgumentException
     */
    protected function _validate(): void {
        $lowerBound = $this->rawLowerBound ?? $this->rawUpperBound;
        $upperBound = $this->rawUpperBound ?? $this->rawLowerBound;
        
        if ($lowerBound > $upperBound) {
            //null-null pair also fails
            throw new \InvalidArgumentException("Lower bound expected to smaller-equal to the upper.");
        }
    }
    
    /**
     * Fails if null, call ->has*Bound() before
     * @return \DateTimeImmutable
     */
    public function getLowerBound(): \DateTimeImmutable {
        return $this->lowerBoundParam->getValue();
    }
    
    /**
     * Fails if null, call ->has*Bound() before
     * @return \DateTimeImmutable
     */
    public function getUpperBound(): \DateTimeImmutable {
        return $this->upperBoundParam->getValue();
    }
    
    
}

