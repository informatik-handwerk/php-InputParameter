<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\InputParameter_List;
use ihde\php\InputParameter\Lang\Instantiable_KeyValue;
use ihde\php\InputParameter\Lang\Type_InputParameter_PositiveInt;
use ihde\php\InputParameter\StringParser;

class InputParameter_List_PositiveInt
    extends InputParameter_List
    implements Type_InputParameter_PositiveInt {
    
    /** @var InputParameter_Single_PositiveInt[]|InputParameter_Range_PositiveInt[] $list */
    protected array $list;
    
    /**
     * @param string $name
     * @param        ...$items
     * @return InputParameter_List_PositiveInt
     * @throws \InvalidArgumentException
     */
    public static function instance_direct(string $name, ...$items): self {
        $list = [];
        
        foreach ($items as $item) {
            if ($item instanceof Type_InputParameter_PositiveInt) {
                $list[] = $item;
                continue;
            }
            
            if (\is_int($item)) {
                $list[] = InputParameter_Single_PositiveInt::instance_direct($name, $item);
                continue;
            }
            
            if (\is_array($item)) {
                //expecting int
                $list[] = InputParameter_Range_PositiveInt::instance_direct($name, ...$item);
                continue;
            }
            
            if (\is_string($item)) {
                if (StringParser::containsRange($item)) {
                    $list[] = InputParameter_Range_PositiveInt::instance_keyValue($name, $item);
                    continue;
                } else {
                    $list[] = InputParameter_Single_PositiveInt::instance_keyValue($name, $item);
                    continue;
                }
            }
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException("Unexpected type");
            // @codeCoverageIgnoreEnd
        }
        
        $instance = new static($name, $items, ...$list);
        return $instance;
    }
    
    /**
     * @implements Instantiable_KeyValue
     * @inheritDoc
     * @throws \InvalidArgumentException
     */
    public static function instance_keyValue($key, $value): self {
        $items = StringParser::splitList($value);
        
        $list = \array_map(static function (string $listItem) use ($key): InputParameter {
            if (StringParser::containsRange($listItem)) {
                return InputParameter_Range_PositiveInt::instance_keyValue($key, $listItem);
            } else {
                return InputParameter_Single_PositiveInt::instance_keyValue($key, $listItem);
            }
        }, $items);
        
        $instance = new static($key, $value, ...$list);
        return $instance;
    }
    
    /**
     * @return InputParameter_Single_PositiveInt[]|InputParameter_Range_PositiveInt[]
     */
    public function getList(): array {
        return parent::getList();
    }
    
    
}

