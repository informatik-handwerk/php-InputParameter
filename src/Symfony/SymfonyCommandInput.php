<?php

declare(strict_types=1);

namespace ihde\php\InputParameter\Symfony;

use ihde\php\InputParameter\InputParameter;
use Symfony\Component\Console\Input\InputInterface;

class SymfonyCommandInput
{
    /** @var string[]|InputParameter[] $map_optionName_transformerClass */
    protected array $map_optionName_transformerClass;
    /** @var InputParameter[]|InputParameter[][] */
    protected array $input;

    /**
     * @param InputInterface            $inputBag
     * @param string[]|InputParameter[] $map_optionName_transformerClass
     * @throws \Exception
     */
    public function __construct(InputInterface $inputBag, array $map_optionName_transformerClass)
    {
        $this->map_optionName_transformerClass = $map_optionName_transformerClass;
        $this->input = static::transform_manyOptions($inputBag, $map_optionName_transformerClass);
    }

    /**
     * @param InputInterface            $inputBag
     * @param string[]|InputParameter[] $map_optionName_transformerClass InputTransformer::class
     * @return array
     * @throws \Exception
     */
    protected static function transform_manyOptions(
        InputInterface $inputBag,
        array $map_optionName_transformerClass
    ): array {
        $result = [];

        foreach ($map_optionName_transformerClass as $name => $transformerClass) {
            $inputSupplied = $inputBag->getOption($name);
            $inputTransformed = static::transform_oneInput($name, $inputSupplied, $transformerClass);
            $result[$name] = $inputTransformed;
        }

        return $result;
    }

    /**
     * Caller is responsible for fetching input and persisting transformed result.
     *
     * @param string                $name
     * @param string|array          $inputSupplied
     * @param string|InputParameter $transformerClass
     * @return InputParameter|InputParameter[]
     * @throws \Exception
     */
    protected static function transform_oneInput(string $name, $inputSupplied, string $transformerClass)
    {

        assert(\is_a($transformerClass, InputParameter::class, true));
        if (\is_array($inputSupplied)) {
            $inputTransformed = \array_map(
                static fn(string $eachInputSupplied) => $transformerClass::instance_keyValue(
                    $name,
                    $eachInputSupplied
                ),
                $inputSupplied
            );
        } else {
            $inputTransformed = $transformerClass::instance_keyValue($name, $inputSupplied);
        }
        
        return $inputTransformed;
    }

    /**
     * @param string $name
     * @return InputParameter|InputParameter[]
     */
    public function getOneInput(string $name)
    {
        assert(\array_key_exists($name, $this->input));
        return $this->input[$name];
    }

    /**
     * @return InputParameter[]|InputParameter[][]
     */
    public function getAllInputs(): array
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $collector = [];

        foreach ($this->input as $name => $input) {
            if (!\is_array($input)) {
                $input = [$input];
            }
            
            foreach ($input as $inputParameter) {
                assert($inputParameter instanceof InputParameter);
                assert($inputParameter->getName() === $name);

                $collector = "--$name='" . $inputParameter->__toString() . "'";
            }
        }

        $result = \implode(" ", $collector);
        return $result;
    }


}

