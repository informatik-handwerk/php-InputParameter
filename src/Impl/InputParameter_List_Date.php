<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Impl;

use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\InputParameter_List;
use ihde\php\InputParameter\Lang\Instantiable_fromStrings;
use ihde\php\InputParameter\Lang\Type_Date;
use ihde\php\InputParameter\StringParser;

class InputParameter_List_Date
    extends InputParameter_List
    implements Type_Date {
    
    /** @var InputParameter_Range_Date[] $list */
    protected array $list = [];
    
    /**
     * @param string $name
     * @param        ...$items
     * @return InputParameter_List_Date
     * @throws \Exception
     */
    public static function instance_direct(string $name, ...$items): self {
        $list = [];
        
        foreach ($items as $item) {
            if ($item instanceof InputParameter_Range_Date) {
                $list[] = $item;
                continue;
            }
            
            if ($item instanceof InputParameter_Single_Date || $item instanceof \DateTimeImmutable) {
                $list[] = InputParameter_Range_Date::instance_directSingle($name, $item);
                continue;
            }
            
            if (\is_string($item) || \is_int($item)) {
                $list[] = InputParameter_Range_Date::instance_fromStrings($name, (string)$item);
                continue;
            }
            
            if (\is_array($item)) {
                $itemAsIP = \array_map(static function ($input) use ($name): ?InputParameter_Single_Date {
                    if ($input === null || $input instanceof InputParameter_Single_Date) {
                        return $input;
                    }
                    
                    if (\is_string($input) || \is_int($input)) {
                        return InputParameter_Single_Date::instance_fromStrings($name, (string)$input);
                    } else {
                        return InputParameter_Single_Date::instance_direct($name, $input);
                    }
                }, $item);
                
                $list[] = new InputParameter_Range_Date($name, $item, ...$itemAsIP);
                continue;
            }
            
            // @codeCoverageIgnoreStart
            throw new \LogicException("Unexpected type");
            // @codeCoverageIgnoreEnd
        }
        
        $instance = new static($name, $items, ...$list);
        return $instance;
    }
    
    /**
     * @implements Instantiable_fromStrings
     * @inheritDoc
     * @throws \InvalidArgumentException|\Exception
     */
    public static function instance_fromStrings(string $key, string $value): self {
        $items = StringParser::splitList($value);
        
        $list = \array_map(static function (string $listItem) use ($key): InputParameter {
            return InputParameter_Range_Date::instance_fromStrings($key, $listItem);
        }, $items);
        
        $instance = new static($key, $value, ...$list);
        return $instance;
    }
    
    /**
     * @return InputParameter_Range_Date[]
     */
    public function getList(): array {
        return parent::getList();
    }
    
    
}

