<?php

declare(strict_types=1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\Instantiable_KeyValue;

abstract class InputParameter implements Instantiable_KeyValue
{
    protected string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param $key
     * @param $value
     * @return static
     * @throws \Exception
     */
    public static function instance_keyValue($key, $value): self
    {
        $instance = new static($key, $value);
        return $instance;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}

