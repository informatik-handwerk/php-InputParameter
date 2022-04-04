<?php

declare(strict_types=1);

namespace App\Command\InputParameter\Impl;

use App\Command\InputParameter\InputParameter_Single;
use App\Command\InputParameter\StringParser;

class InputParameter_Single_String extends InputParameter_Single
{
    protected string $value;

    /**
     * @param string $name
     * @param string $input
     */
    public function __construct(string $name, string $input)
    {
        parent::__construct($name);
        $this->value = StringParser::parse_string($input);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }


}

