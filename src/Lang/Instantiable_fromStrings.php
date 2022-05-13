<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Lang;

use ihde\php\InputParameter\InputParameter;

interface Instantiable_fromStrings
    extends Instantiable_KeyValue {
    
    /**
     * @param string $key
     * @param string $value
     * @return InputParameter
     */
    public static function instance_fromStrings(string $key, string $value): InputParameter;
    
    
}

