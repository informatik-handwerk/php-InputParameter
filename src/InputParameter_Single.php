<?php

declare(strict_types=1);

namespace ihde\php\InputParameter;

abstract class InputParameter_Single extends InputParameter
{

    /**
     * @return mixed
     */
    abstract public function getValue();


}

