<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LogicalOr extends BinaryOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() || $this->right->evaluate();
    }
}
