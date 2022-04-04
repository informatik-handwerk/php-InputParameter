<?php

declare(strict_types=1);

namespace App\Command\InputParameter;

abstract class InputParameter_Single extends InputParameter
{

    /**
     * @return mixed
     */
    abstract public function getValue();


}

