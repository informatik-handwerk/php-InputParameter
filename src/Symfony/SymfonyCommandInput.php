<?php

declare(strict_types=1);

namespace ihde\php\InputParameter\Symfony;

use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use Symfony\Component\Console\Input\InputInterface;

class SymfonyCommandInput
{
    /** @var string[] $map_optionName_transformerClass */
    protected array $map_optionName_transformerClass;
    protected array $input;

    /**
     * @param InputInterface $inputBag
     * @param array $map_optionName_transformerClass
     * @throws \Exception
     */
    public function __construct(InputInterface $inputBag, array $map_optionName_transformerClass)
    {
        $this->map_optionName_transformerClass = $map_optionName_transformerClass;
        $this->input = static::transform_manyOptions($inputBag, $map_optionName_transformerClass);
    }

    /**
     * @param InputInterface $inputBag
     * @param string[] $map_optionName_transformerClass InputTransformer::class
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
     * @param string $name
     * @param string|array $inputSupplied
     * @param string $transformerClass
     * @return array|mixed
     */
    protected static function transform_oneInput(string $name, $inputSupplied, string $transformerClass)
    {
        assert(\is_a($transformerClass, Instantiable_KeyValue::class, true));

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

        return $inputTransformed; //ok to push value to null - bumps up to array
    }

    /**
     * @param string $name
     * @return array|mixed
     */
    public function getOneInput(string $name)
    {
        assert(\array_key_exists($name, $this->input));
        return $this->input[$name];
    }

    /**
     * @return array
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
            if ($input instanceof InputParameter) {
                $input = [$input];
            }

            assert(\is_array($input));
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

