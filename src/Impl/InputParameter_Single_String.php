<?php

declare(strict_types=1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_Single;

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
        $this->value = $input;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }


}

