<?php

declare(strict_types=1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;
use ihde\php\InputParameter\StringParser;

class InputParameter_Single_Date extends InputParameter_Single
{
    protected \DateTimeImmutable $value;

    /**
     * @param string $name
     * @param string $input
     * @throws \Exception
     */
    public function __construct(string $name, string $input)
    {
        parent::__construct($name);
        $this->value = StringParser::parse_date($input);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getValue(): \DateTimeImmutable
    {
        return $this->value;
    }


}

