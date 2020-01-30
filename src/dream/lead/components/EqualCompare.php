<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class EqualCompare extends BinaryOperator
{
    public function evaluate()
    {
        return ($this->left->evaluate() == $this->right->evaluate());
    }
}
