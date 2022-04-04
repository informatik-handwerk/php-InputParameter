<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Lang;

interface Instantiable_KeyValue {
    
    /**
     * @param $key
     * @param $value
     * @return object
     */
    public static function instance_keyValue($key, $value): object;
    
    
}

