<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Range;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_Date
    extends InputParameter_Range {
    
    /**
     * @param string                          $name
     * @param                                 $seed
     * @param InputParameter_Single_Date|null $lowerBound
     * @param InputParameter_Single_Date|null $upperBound
     * @throws \InvalidArgumentException
     */
    protected function __construct(
        string $name,
        $seed,
        ?InputParameter_Single_Date $lowerBound,
        ?InputParameter_Single_Date $upperBound
    ) {
        parent::__construct($name, $seed, $lowerBound, $upperBound);
        $this->_validate();

    }
    /**
     * @param          $name
     * @param \DateTimeImmutable|null $lowerBound
     * @param \DateTimeImmutable|null $upperBound
     * @return InputParameter_Range_Date
     * @throws \InvalidArgumentException|\Exception
     */
    public static function instance_direct(
        $name,
        ?\DateTimeImmutable $lowerBound,
        ?\DateTimeImmutable $upperBound
    ): InputParameter_Range_Date {
        $stringLowerBound = ($lowerBound === null)
            ? ""
            : (string)$lowerBound->getTimestamp();
        $stringUpperBound = ($upperBound === null)
            ? ""
            : (string)$upperBound->getTimestamp();
        $seed = $stringLowerBound. StringParser::SPLITTER_range . $stringUpperBound;
    
        $instanceLowerBound = ($lowerBound === null)
            ? null
            : InputParameter_Single_Date::instance_keyValue($name, $lowerBound);
        $instanceUpperBound = ($upperBound === null)
            ? null
            : InputParameter_Single_Date::instance_keyValue($name, $upperBound);
        
        $instance = new static($name, $seed, $instanceLowerBound, $instanceUpperBound);
        return $instance;
    }
    
    /**
     * @implements Instantiable_KeyValue
     * @inheritDoc
     * @throws \InvalidArgumentException|\Exception
     */
    public static function instance_keyValue($key, $value): self {
        $range = StringParser::splitRange($value);
        [$rawLowerBound, $rawUpperBound] = $range;
        
        $lowerBound = ($rawLowerBound === null)
            ? null
            : InputParameter_Single_Date::instance_keyValue($key, $rawLowerBound);
        $upperBound = ($rawUpperBound === null)
            ? null
            : InputParameter_Single_Date::instance_keyValue($key, $rawUpperBound);
        
        $instance = new static($key, $value, $lowerBound, $upperBound);
        return $instance;
    }
    
    /**
     * @inheritDoc
     * @return \DateTimeImmutable
     */
    public function getLowerBound(): \DateTimeImmutable {
        return $this->lowerBound->getValue();
    }
    
    /**
     * @inheritDoc
     * @return \DateTimeImmutable
     */
    public function getUpperBound(): \DateTimeImmutable {
        return $this->upperBound->getValue();
    }
    
    
}

