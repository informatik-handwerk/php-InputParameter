<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Range;

class InputParameter_Range_PositiveInt
    extends InputParameter_Range {
    protected ?InputParameter_Single_PositiveInt $lowerBoundParam = null;
    protected ?InputParameter_Single_PositiveInt $upperBoundParam = null;
    
    /**
     * @throws \Exception
     */
    public function __construct(string $name, string $input) {
        parent::__construct($name, $input);
    
        if ($this->hasLowerBound()) {
            $this->lowerBoundParam = new InputParameter_Single_PositiveInt($name, $this->rawLowerBound);
        }
        if ($this->hasUpperBound()) {
            $this->upperBoundParam = new InputParameter_Single_PositiveInt($name, $this->rawUpperBound);
        }
        
        $this->_validate();
    }
    
    /**
     * @throws \InvalidArgumentException
     */
    protected function _validate(): void {
        $lowerBound = $this->lowerBoundParam ?? $this->upperBoundParam;
        $upperBound = $this->upperBoundParam ?? $this->lowerBoundParam;
        
        if ($lowerBound > $upperBound) {
            //null-null pair also fails
            throw new \InvalidArgumentException("Lower bound expected to smaller-equal to the upper.");
        }
    }
    
    /**
     * Fails if null, call ->has*Bound() before
     * @return InputParameter_Single_PositiveInt
     */
    public function getLowerBound(): InputParameter_Single_PositiveInt {
        return $this->lowerBoundParam;
    }
    
    /**
     * Fails if null, call ->has*Bound() before
     * @return InputParameter_Single_PositiveInt
     */
    public function getUpperBound(): InputParameter_Single_PositiveInt {
        return $this->upperBoundParam;
    }
    
    
}

