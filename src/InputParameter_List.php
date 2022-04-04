<?php

declare(strict_types=1);

namespace ihde\php\InputParameter;

abstract class InputParameter_List extends InputParameter implements \Countable
{

    protected string $name;
    protected array $rawList = [];

    /**
     * @param string $input examples: "20, 30", "20,30", "20", "20,, ,30,"
     * @throws \Exception
     */
    public function __construct(string $name, string $input)
    {
        parent::__construct($name);
        $this->rawList = StringParser::splitList($input);
    }

    /**
     * @return bool
     */
    public function hasItems(): bool
    {
        return \count($this) > 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->rawList);
    }

    /**
     * @return InputParameter[]
     */
    public function getList(): array  {
        return $this->list;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $asString = \implode(StringParser::SPLITTER_list, $this->rawList);
        return $asString;
    }


}

