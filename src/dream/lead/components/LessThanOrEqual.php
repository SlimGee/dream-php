<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LessThanOrEqual extends BinaryOperator
{
    public function evaluate()
    {
        return $this->left()->evaluate() <= $this->right->evaluate();
    }
}
