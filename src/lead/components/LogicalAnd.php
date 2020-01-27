<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LogicalAnd extends BinaryOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() && $this->right->evaluate();
    }
}
