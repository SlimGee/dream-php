<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class Block implements IExpression
{
    private $expressions = [];

    public function addExpression($expression)
    {
        $this->expressions[] = $expression;
    }

    public function getExpressions()
    {
        return $this->expressions;
    }

    public function evaluate()
    {
        foreach ($this->expressions as  $value)
        {
            $value->evaluate();
        }
    }
}
