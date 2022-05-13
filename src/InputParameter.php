<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\Instantiable_fromStrings;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue_TRAIT_fromStrings;

abstract class InputParameter
    implements Input,
               Instantiable_fromStrings {
    
    use Instantiable_KeyValue_TRAIT_fromStrings;
    
    protected string $name;
    /** @var ?string|mixed $seed */
    protected $seed;
    
    /**
     * @param string $name
     * @param        $seed
     */
    protected function __construct(string $name, $seed) {
        $this->name = $name;
        $this->seed = $seed;
    }
    
    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }
    
    
}

