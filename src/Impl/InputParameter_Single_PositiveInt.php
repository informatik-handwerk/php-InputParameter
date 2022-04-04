<?php

declare(strict_types=1);

namespace App\Command\InputParameter\Impl;

use App\Command\InputParameter\InputParameter_Single;
use App\Command\InputParameter\StringParser;

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

