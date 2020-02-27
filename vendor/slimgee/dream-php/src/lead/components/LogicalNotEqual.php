<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LogicalNotEqual extends BinaryOperator
{
    public function evaluate()
    {
        return ($this->left->evaluate() !== $this->left->evaluate());
    }
}
