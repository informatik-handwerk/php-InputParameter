<?php

declare(strict_types=1);

namespace App\Command\InputParameter;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\Expression;

class SymfonyDoctrineBridge
{
    /** @var string[] $nameMap */
    protected array $nameMap;

    /**
     * @param string[] $nameMap
     */
    protected function __construct(array $nameMap = [])
    {
        $this->nameMap = $nameMap;
    }

    /**
     * @param array $nameMap
     * @return static
     */
    public static function instance(array $nameMap = []): self
    {
        return new self($nameMap);
    }

    /**
     * @param InputParameter $param
     * @return string
     */
    public function parameterToColumnName(InputParameter $param): string
    {
        $nameParam = $param->getName();
        $nameColumn = $this->nameMap[$nameParam] ?? $nameParam;
        return $nameColumn;
    }

    /**
     * @param InputParameter ...$params
     * @return Criteria
     */
    public function parametersAsCriteria(InputParameter ...$params): Criteria
    {
        $criteria = Criteria::create();

        $expression = $this->parametersAsExpression(...$params);
        $criteria->where($expression);

        return $criteria;
    }

    /**
     * @param InputParameter ...$params
     * @return Expression
     */
    public function parametersAsExpression(InputParameter ...$params): Expression
    {
        $expressions = [];

        foreach ($params as $param) {
            $expressions[] = $this->oneAsExpression($param);
        }

        if (\count($expressions) === 1) {
            return \reset($expressions);
        }

        $result = new CompositeExpression(
            CompositeExpression::TYPE_AND,
            $expressions
        );

        return $result;
    }

    /**
     * @param InputParameter $param
     * @return Expression
     */
    protected function oneAsExpression(InputParameter $param): Expression
    {
        if ($param instanceof InputParameter_Single) {
            return $this->oneAsExpression_Single($param);
        }

        if ($param instanceof InputParameter_Range) {
            return $this->oneAsExpression_Range($param);
        }

        if ($param instanceof InputParameter_List) {
            return $this->oneAsExpression_List($param);
        }

        throw new \LogicException("Switch fallthrough");
    }

    /**
     * @param InputParameter_Single $param
     * @return Expression
     */
    protected function oneAsExpression_Single(
        InputParameter_Single $param
    ): Expression {
        $columnName = $this->parameterToColumnName($param);

        $result = Criteria::expr()
            ->eq($columnName, $param->getValue());

        return $result;
    }

    /**
     * @param InputParameter_Range $param
     * @return Expression
     */
    protected function oneAsExpression_Range(
        InputParameter_Range $param
    ): Expression {
        $columnName = $this->parameterToColumnName($param);

        $expressions = [];

        if ($param->hasLowerBound()) {
            $expressions[] = Criteria::expr()
                ->gte($columnName, $param->getLowerBound());
        }

        if ($param->hasUpperBound()) {
            $expressions[] = Criteria::expr()
                ->lte($columnName, $param->getUpperBound());
        }

        if (\count($expressions) === 1) {
            return \reset($expressions);
        }

        return new CompositeExpression(
            CompositeExpression::TYPE_AND,
            $expressions
        );
    }

    /**
     * @param InputParameter_List $param
     * @return Expression
     */
    protected function oneAsExpression_List(
        InputParameter_List $param
    ): Expression {
        $expressions = [];

        $subParams = $param->getList();
        foreach ($subParams as $subParam) {
            $expressions[] = $this->oneAsExpression($subParam);
        }

        if (\count($expressions) === 1) {
            return \reset($expressions);
        }

        return new CompositeExpression(
            CompositeExpression::TYPE_OR,
            $expressions
        );
    }
}

