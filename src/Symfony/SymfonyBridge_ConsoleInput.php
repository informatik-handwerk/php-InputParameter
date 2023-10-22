<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Symfony;

use ihde\php\InputParameter\Input;
use ihde\php\InputParameter\InputCollection;
use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\Lang\Instantiable_fromStrings;
use Symfony\Component\Console\Input\InputInterface;

class SymfonyBridge_ConsoleInput {
    /** @var string[]|Instantiable_fromStrings[] $map_optionName_transformerClass */
    protected array $map_optionName_transformerClass;
    protected InputCollection $inputParameterCollection;
    
    /**
     * @param InputInterface            $inputBag
     * @param string[]|InputParameter[] $map_optionName_transformerClass
     * @throws \Exception
     */
    public function __construct(InputInterface $inputBag, array $map_optionName_transformerClass) {
        $this->map_optionName_transformerClass = $map_optionName_transformerClass;
        
        $this->inputParameterCollection = InputCollection::instance();
        $allInputParameters = static::transform_manyOptions($inputBag, $map_optionName_transformerClass);
        
        foreach ($allInputParameters as $name => $eachInputParameters) {
            $this->inputParameterCollection->add(...$eachInputParameters);
        }
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
            $inputTransformed = static::transform_oneOption($name, $inputBag, $transformerClass);
            $result[$name] = $inputTransformed;
        }
        
        return $result;
    }
    
    /**
     * Caller is responsible for fetching input and persisting transformed result.
     *
     * @param string                          $name
     * @param InputInterface                  $inputBag
     * @param string|Instantiable_fromStrings $transformerClass
     * @return InputParameter[]
     * @throws \Exception
     */
    protected static function transform_oneOption(string $name, InputInterface $inputBag, string $transformerClass): array {
        assert(\is_a($transformerClass, Instantiable_fromStrings::class, true));
        
        $inputSupplied = $inputBag->getOption($name);
        
        if (\is_array($inputSupplied)) {
            $inputTransformed = \array_map(
                static fn(string $eachInputSupplied) => $transformerClass::instance_fromStrings(
                    $name,
                    $eachInputSupplied
                ),
                $inputSupplied
            );
        } else {
            $inputTransformed = [$transformerClass::instance_fromStrings($name, $inputSupplied)];
        }
        
        return $inputTransformed;
    }
    
    /**
     * @return InputParameter[]
     */
    public function getInputs_all(): array {
        $result = $this->inputParameterCollection->getAllParameters();
        return $result;
    }
    
    /**
     * @param Input $input
     * @return string
     */
    public static function inputToString(Input $input): string {
        if ($input instanceof InputParameter) {
            return static::inputParameterToString($input);
        }
        
        if ($input instanceof InputCollection) {
            return static::inputCollectionToString($input);
        }
        
        throw new \TypeError(\get_class($input));
    }
    
    /**
     * @param InputParameter $inputParameter
     * @return string
     */
    public static function inputParameterToString(InputParameter $inputParameter): string {
        $name = $inputParameter->getName();
        $value = $inputParameter->__toString();
        $result = "--$name='$value'";
        return $result;
    }
    
    /**
     * @param InputCollection $collection
     * @return string
     */
    public static function inputCollectionToString(InputCollection $collection): string {
        $collector = [];
        
        $inputs = $collection->getAllParameters();
        foreach ($inputs as $input) {
            $collector[] = static::inputParameterToString($input);
        }
        
        $inputParametersByName = \implode(" ", $collector);
        return $inputParametersByName;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        $result = static::inputCollectionToString($this->inputParameterCollection);
        return $result;
    }
    
    
}

