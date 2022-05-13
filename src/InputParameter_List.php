<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\Form_composite;

abstract class InputParameter_List
    extends InputParameter
    implements Form_composite {
    
    /** @var InputParameter[] $list */
    protected array $list;
    
    /**
     * @param string         $name
     * @param                $seed
     * @param InputParameter ...$items
     */
    public function __construct(string $name, $seed, InputParameter ...$items) {
        $names = \array_map(static fn(InputParameter $ip): string => $ip->getName(), $items);
        $names[] = $name;
        $names = \array_unique($names);
        if (\count($names) !== 1) {
            throw new \DomainException("Expecting common name, received: ", \implode(", ", $names));
        }
        
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
        return \count($this->list) > 0;
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

