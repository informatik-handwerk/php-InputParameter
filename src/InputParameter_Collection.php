<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

class InputParameter_Collection {
    
    /** @var InputParameter[][] $itemsByName */
    protected array $itemsByName;
    
    /**
     * @param InputParameter ...$items
     */
    public function __construct(InputParameter ...$items) {
        foreach ($items as $eachItem) {
            $eachName = $eachItem->getName();
            $this->itemsByName[$eachName][] = $eachName;
        }
    }
    
    /**
     * @return InputParameter[][]
     */
    public function getAllByName(): array {
        return $this->itemsByName;
    }
    
    /**
     * @return InputParameter[]
     */
    public function getAllFlatened(): array {
        $result = [];
        foreach ($this->itemsByName as $items) {
            \array_push($result, ...$items);
        }
        
        return $result;
    }
    
    /**
     * @return InputParameter[][]
     */
    public function getForName(string $name): array {
        $result = $this->itemsByName[$name] ?? [];
        return $result;
    }
    
    
}

