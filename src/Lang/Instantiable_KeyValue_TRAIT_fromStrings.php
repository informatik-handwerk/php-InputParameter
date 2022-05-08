<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Lang;

trait Instantiable_KeyValue_TRAIT_fromStrings {
    
    /**
     * @implements Instantiable_KeyValue
     * @inheritDoc
     */
    public static function instance_keyValue($key, $value): object {
        return static::instance_fromStrings($key, $value);
    }
    
    
}

