<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\Form_composite;
use ihde\php\InputParameter\Lang\Type_mixed;

class InputParameter_Collection
    implements Input,
               Form_composite,
               Type_mixed {
    
    /** @var Input[] $items */
    protected array $items = [];
    
    /**
     *
     */
    protected function __construct() {
    }
    
    /**
     * @return static
     */
    public static function instance(): self {
        return new static();
    }
    
    /**
     * @return static
     */
    public function cloneInstance(): self {
        $instance = new static();
        $instance->add(...$this->items);
        return $instance;
    }
    
    /**
     * @param Input ...$input
     * @return $this
     */
    public function add(Input ...$input): self {
        \array_push($this->items, ...$input);
        return $this;
    }
    
    /**
     * @return Input[]
     */
    public function getAllInputs(): array {
        $result = $this->items;
        return $result;
    }
    
    /**
     * @return InputParameter[]
     */
    public function getAllParameters(): array {
        $result = [];
        foreach ($this->items as $item) {
            if ($item instanceof self) {
                \array_push($result, ...$item->getAllParameters());
            } else {
                $result[] = $item;
            }
        }
        
        return $result;
    }
    
    
}

