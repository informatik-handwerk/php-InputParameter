<?php

declare(strict_types=1);

namespace App\Command\InputParameter\Impl;

use App\Command\InputParameter\InputParameter_Single;
use App\Command\InputParameter\StringParser;

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

