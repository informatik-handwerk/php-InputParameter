<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Range;
use ihde\php\InputParameter\Lang\Instantiable_fromStrings;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use ihde\php\InputParameter\Lang\Type_Date;
use ihde\php\InputParameter\StringParser;

class InputParameter_Range_Date
    extends InputParameter_Range
    implements Type_Date {
    
    /**
     * @param string                          $name
     * @param                                 $seed
     * @param InputParameter_Single_Date|null $lowerBound
     * @param InputParameter_Single_Date|null $upperBound
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $name,
        $seed,
        ?InputParameter_Single_Date $lowerBound,
        ?InputParameter_Single_Date $upperBound
    ) {
        parent::__construct($name, $seed, $lowerBound, $upperBound);
        $this->_validate();
        
    }
    
    /**
     * @param \DateTimeImmutable $date
     * @param bool               $midnight
     * @param string             $intervalString
     * @return \DateTimeImmutable[]
     * @throws \Exception
     */
    public static function toRange(
        \DateTimeImmutable $date,
        bool $midnight = true,
        string $intervalString = "P1D"
    ): array {
        $dateFrom = $midnight
            ? $date->setTime(0, 0, 0)
            : $date;
        $dateTo = $dateFrom->add(new \DateInterval($intervalString));
        
        $result = [$dateFrom, $dateTo];
        return $result;
    }
    
    /**
     * @param                                               $name
     * @param \DateTimeImmutable|InputParameter_Single_Date $dateSource
     * @param bool                                          $midnight
     * @param string                                        $intervalString
     * @return InputParameter_Range_Date
     * @throws \InvalidArgumentException|\Exception
     */
    public static function instance_directSingle(
        $name,
        $dateSource,
        bool $midnight = true,
        string $intervalString = "P1D"
    ): InputParameter_Range_Date {
        if ($dateSource instanceof InputParameter_Single_Date) {
            $date = $dateSource->getValue();
        } elseif ($dateSource instanceof \DateTimeImmutable) {
            $date = $dateSource;
        } else {
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException("unexpected tyepe");
            // @codeCoverageIgnoreEnd
        }
        
        [$lowerBound, $upperBound] = self::toRange($date, $midnight, $intervalString);
        
        $instanceLowerBound = InputParameter_Single_Date::instance_direct($name, $lowerBound);
        $instanceUpperBound = InputParameter_Single_Date::instance_direct($name, $upperBound);
        
        $instance = new static($name, $dateSource, $instanceLowerBound, $instanceUpperBound);
        return $instance;
    }
    
    /**
     * @param                         $name
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
        $seed = [$lowerBound, $upperBound];
        
        $instanceLowerBound = ($lowerBound === null)
            ? null
            : InputParameter_Single_Date::instance_direct($name, $lowerBound);
        $instanceUpperBound = ($upperBound === null)
            ? null
            : InputParameter_Single_Date::instance_direct($name, $upperBound);
        
        $instance = new static($name, $seed, $instanceLowerBound, $instanceUpperBound);
        return $instance;
    }
    
    /**
     * @implements Instantiable_fromStrings
     * @inheritDoc
     * @throws \InvalidArgumentException|\Exception
     */
    public static function instance_fromStrings(string $key, string $value): self {
        if (StringParser::containsStandalone($value)) {
            $date = StringParser::parse_date($value);
            $range = self::toRange($date);
            $instance = new static(
                $key,
                $value,
                InputParameter_Single_Date::instance_direct($key, $range[0]),
                InputParameter_Single_Date::instance_direct($key, $range[1]),
            );
            return $instance;
        }
        
        $range = StringParser::splitRange($value); //(StringParser::containsRange($value);
        [$rawLowerBound, $rawUpperBound] = $range;
        
        $lowerBound = ($rawLowerBound === null)
            ? null
            : InputParameter_Single_Date::instance_fromStrings($key, $rawLowerBound);
        $upperBound = ($rawUpperBound === null)
            ? null
            : InputParameter_Single_Date::instance_fromStrings($key, $rawUpperBound);
        
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

