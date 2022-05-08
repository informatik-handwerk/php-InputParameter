<?php

declare(strict_types = 1);

namespace ihde\php\InputParameter\Symfony;

use Doctrine\ORM\Query\Expr;
use ihde\php\InputParameter\Input;
use ihde\php\InputParameter\InputParameter;
use ihde\php\InputParameter\InputParameter_Collection;
use ihde\php\InputParameter\InputParameter_List;
use ihde\php\InputParameter\InputParameter_Range;
use ihde\php\InputParameter\InputParameter_Single;

class SymfonyBridge_DoctrineOrmQueryExpr {
    /** @var string[] $nameMap */
    protected array $nameMap;
    protected Expr $exprFactory;
    
    /**
     * @param string[] $nameMap
     */
    protected function __construct(array $nameMap = []) {
        $this->nameMap = $nameMap;
        $this->exprFactory = new Expr();
    }
    
    /**
     * @param array $nameMap
     * @return static
     */
    public static function instance(array $nameMap = []): self {
        return new self($nameMap);
    }
    
    /**
     * @param InputParameter $param
     * @return string
     */
    public function parameterToColumnName(InputParameter $param): string {
        $nameParam = $param->getName();
        $nameColumn = $this->nameMap[$nameParam] ?? $nameParam;
        return $nameColumn;
    }
    
    /**
     * @param Input ...$inputs
     * @return Expr\Andx
     * @throws \LogicException
     */
    public function parametersAsExpression(Input ...$inputs): Expr\Andx {
        $result = $this->andManyToExpression($inputs);
        return $result;
    }
    
    /**
     * @param Input $input
     * @return Expr\Andx|Expr\Comparison|Expr\Orx
     * @throws \LogicException
     */
    protected function oneAsExpression(Input $input) {
        if ($input instanceof InputParameter_Single) {
            return $this->oneAsExpression_Single($input);
        }
        
        if ($input instanceof InputParameter_Range) {
            return $this->oneAsExpression_Range($input);
        }
        
        if ($input instanceof InputParameter_List) {
            return $this->oneAsExpression_List($input);
        }
        
        if ($input instanceof InputParameter_Collection) {
            return $this->oneAsExpression_Collection($input);
        }
        
        throw new \LogicException("Switch fallthrough");
    }
    
    /**
     * @param InputParameter_Single $param
     * @return Expr\Comparison
     */
    protected function oneAsExpression_Single(InputParameter_Single $param): Expr\Comparison {
        $columnName = $this->parameterToColumnName($param);
        
        $result = $this->exprFactory->eq($columnName, $param->getValue());
        
        return $result;
    }
    
    /**
     * @param InputParameter_Range $param
     * @return Expr\Andx
     */
    protected function oneAsExpression_Range(InputParameter_Range $param): Expr\Andx {
        $columnName = $this->parameterToColumnName($param);
        
        $expressions = [];
        
        if ($param->hasLowerBound()) {
            $expressions[] = $this->exprFactory
                ->gte($columnName, $param->getLowerBound());
        }
        
        if ($param->hasUpperBound()) {
            $expressions[] = $this->exprFactory
                ->lt($columnName, $param->getUpperBound());
        }
        
        if (\count($expressions) === 1) {
            return \reset($expressions);
        }
        
        return $this->exprFactory->andX(...$expressions);
    }
    
    /**
     * @param InputParameter_List $param
     * @return Expr\Orx
     */
    protected function oneAsExpression_List(InputParameter_List $param): Expr\Orx {
        $expressions = [];
        
        $subParams = $param->getList();
        foreach ($subParams as $subParam) {
            $expressions[] = $this->oneAsExpression($subParam);
        }
        
        if (\count($expressions) === 1) {
            return \reset($expressions);
        }
        
        return $this->exprFactory->orX(...$expressions);
    }
    
    /**
     * @param \ihde\php\InputParameter\InputParameter_Collection $param
     * @return Expr\Andx
     */
    public function oneAsExpression_Collection(InputParameter_Collection $param): Expr\Andx {
        $items = $param->getAll();
        $result = $this->andManyToExpression($items);
        return $result;
    }
    
    /**
     * @param Input[] $items
     * @return Expr\Andx
     */
    public function andManyToExpression(array $items): Expr\Andx {
        $expressions = [];
        
        foreach ($items as $item) {
            $expressions[] = $this->oneAsExpression($item);
        }
        
        if (\count($expressions) === 1) {
            return \reset($expressions);
        }
        
        $result = $this->exprFactory->andX(
            ...$expressions
        );
        
        return $result;
    }
    
    
}

