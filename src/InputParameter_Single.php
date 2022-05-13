<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter;

use ihde\php\InputParameter\Lang\Form_simple;

abstract class InputParameter_Single
    extends InputParameter
    implements Form_simple {
    
    /**
     * @return mixed
     */
    abstract public function getValue();
    
    
}

