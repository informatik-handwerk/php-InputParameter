<?php

declare(strict_types=1);

namespace App\Command\InputTransformer;

interface Instantiable_KeyValue
{

    /**
     * @param $key
     * @param $value
     * @return object
     */
    public static function instance_keyValue($key, $value);


}

