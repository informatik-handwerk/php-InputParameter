<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

abstract class InputParameter_List
    extends InputParameter
    implements \Countable {
    
    /** @var InputParameter[] $list */
    protected array $list;
    
    /**
     * @param string         $name
     * @param                $seed
     * @param InputParameter ...$items
     */
    protected function __construct(string $name, $seed, InputParameter ...$items) {
        parent::__construct($name, $seed);
        $this->list = $items;
    }
    
    /**
     * @param string $name
     * @param        ...$items
     * @return InputParameter_List
     */
    abstract public static function instance_direct(string $name, ...$items): self;
    
    /**
     * @return bool
     */
    public function hasItems(): bool {
        return \count($this) > 0;
    }
    
    /**
     * @return int
     */
    public function count(): int {
        return \count($this->list);
    }
    
    /**
     * @return InputParameter[]
     */
    public function getList(): array {
        return $this->list;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        if (\is_string($this->seed)) {
            return $this->seed;
        }
        
        $asString = \implode(StringParser::SPLITTER_list, $this->list); //->__toString()
        return $asString;
    }
    
    
}

