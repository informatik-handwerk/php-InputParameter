<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter_List;

class InputParameter_List_Date
    extends InputParameter_List {
    
    /** @var InputParameter_Range_Date[] $list */
    protected array $list = [];
    
    /**
     * @param string $name
     * @param string $input
     * @throws \Exception
     */
    public function __construct(string $name, string $input) {
        parent::__construct($name, $input);
        
        $this->list = \array_map(
            static function (string $listItem) use ($name): InputParameter_Range_Date {
                return new InputParameter_Range_Date($name, $listItem);
            }
            , $this->rawList
        );
    }
    
    /**
     * @return InputParameter_Range_Date[]
     */
    public function getList(): array {
        return parent::getList();
    }
    
    
}

