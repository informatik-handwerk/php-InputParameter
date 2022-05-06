<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

abstract class InputParameter_List_CommonNamed
    extends InputParameter_List {
    
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
        
        parent::__construct($name, $seed, ...$items);
    }
    
    
}

