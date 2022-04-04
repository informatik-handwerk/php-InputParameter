<?php

declare(strict_types=1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\InputParameter_List;
use ihde\php\InputParameter\StringParser;

class InputParameter_List_PositiveInt extends InputParameter_List
{

    /** @var InputParameter[] $list */
    protected array $list = [];

    /**
     * @param string $name
     * @param string $input
     * @throws \Exception
     */
    public function __construct(string $name, string $input)
    {
        parent::__construct($name, $input);

        $this->list = \array_map(static function (string $listItem) use ($name): InputParameter {
            if (StringParser::containsRange($listItem)) {
                return new InputParameter_Range_PositiveInt($name, $listItem);
            } else {
                return new InputParameter_Single_PositiveInt($name, $listItem);
            }
        }, $this->rawList);
    }

    /**
     * @return InputParameter[]
     */
    public function getList(): array
    {
        return $this->list;
    }


}

