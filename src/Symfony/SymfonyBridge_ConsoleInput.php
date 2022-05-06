<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Symfony;

use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\InputParameter_Collection;
use Symfony\Component\Console\Input\InputInterface;

class SymfonyBridge_ConsoleInput {
    /** @var string[]|InputParameter[] $map_optionName_transformerClass */
    protected array $map_optionName_transformerClass;
    protected InputParameter_Collection $inputParameterCollection;
    
    /**
     * @param InputInterface            $inputBag
     * @param string[]|InputParameter[] $map_optionName_transformerClass
     * @throws \Exception
     */
    public function __construct(InputInterface $inputBag, array $map_optionName_transformerClass) {
        $this->map_optionName_transformerClass = $map_optionName_transformerClass;
        
        $allInputParameters = static::transform_manyOptions($inputBag, $map_optionName_transformerClass);
        $this->inputParameterCollection = InputParameter_Collection::instance_direct("", ...$allInputParameters);
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
            
            if (\is_array($inputTransformed)) {
                \array_push($result, ...$inputTransformed);
            } else {
                $result[] = $inputTransformed;
            }
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
    protected static function transform_oneInput(string $name, $inputSupplied, string $transformerClass) {
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
     * @return InputParameter[]
     */
    public function getInputs_forName(string $name): array {
        $result = $this->inputParameterCollection->getForName($name);
        return $result;
    }
    
    /**
     * @return InputParameter[][]
     */
    public function getInputs_all(): array {
        $result = $this->inputParameterCollection->getAllByName();
        return $result;
    }
    
    /**
     * @param InputParameter_Collection $collection
     * @return string
     */
    public static function collectionToString(InputParameter_Collection $collection): string {
        $collector = [];
        
        $inputParameters = $collection->getAllFlatened();
        foreach ($inputParameters as $inputParameter) {
            $name = $inputParameter->getName();
            $value = $inputParameter->__toString();
            $collector[] = "--$name='$value'";
        }
        
        $inputParametersByName = \implode(" ", $collector);
        return $inputParametersByName;
    }
    
    /**
     * @return string
     */
    public function __toString(): string {
        $result = static::collectionToString($this->inputParameterCollection);
        return $result;
    }
    
    
}

