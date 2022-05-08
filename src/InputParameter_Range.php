<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\Form_simple;

abstract class InputParameter_Range
    extends InputParameter
    implements Form_simple {

    protected ?InputParameter_Single $lowerBound;
    protected ?InputParameter_Single $upperBound;

    /**
     * @param string                     $name
     * @param                            $seed
     * @param InputParameter_Single|null $lowerBound
     * @param InputParameter_Single|null $upperBound
     * @throws \InvalidArgumentException
     */
    protected function __construct(
        string $name,
        $seed,
        ?InputParameter_Single $lowerBound,
        ?InputParameter_Single $upperBound
    ) {
        parent::__construct($name, $seed);

        $this->lowerBound = $lowerBound;
        $this->upperBound = $upperBound;

        $this->_validate();
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function _validate(): void {
        $lowerBound = ($this->lowerBound === null)
            ? null
            : $this->lowerBound->getValue();
        $upperBound = ($this->upperBound === null)
            ? null
            : $this->upperBound->getValue();

        $lowerBound = $lowerBound ?? $upperBound;
        $upperBound = $upperBound ?? $lowerBound;

        if ($lowerBound > $upperBound) {
            //null-null pair also fails
            throw new \InvalidArgumentException("Lower bound expected to smaller-equal to the upper.");
        }
    }

//    /**
//     * @param     $name
//     * @param     $lowerBound
//     * @param     $upperBound
//     * @return InputParameter_Range
//     */
//    abstract public static function instance_direct($name, $lowerBound, $upperBound): InputParameter_Range;

    /**
     * @return bool
     */
    public function hasLowerBound(): bool {
        return $this->lowerBound !== null;
    }

    /**
     * @return bool
     */
    public function hasUpperBound(): bool {
        return $this->upperBound !== null;
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
        if (\is_string($this->seed)) {
            return $this->seed;
        }

        $asString = $this->lowerBound->__toString() . StringParser::SPLITTER_range . $this->upperBound->__toString();
        return $asString;
    }


}

