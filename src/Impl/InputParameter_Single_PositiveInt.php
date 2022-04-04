<?php

declare(strict_types=1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;
use ihde\php\InputParameter\StringParser;

class InputParameter_Single_PositiveInt extends InputParameter_Single
{
    protected int $value;

    /**
     * @param string $name
     * @param string $input
     */
    public function __construct(string $name, string $input)
    {
        parent::__construct($name);
        $this->value = StringParser::parse_positiveInt($input);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }


}

