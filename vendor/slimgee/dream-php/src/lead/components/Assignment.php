<?php
namespace Lead\Components;
/**
 *
 */
class Assignment extends BinaryOperator
{
    public function evaluate()
    {
        return $this->left->setValue($this->right->evaluate());
    }
}
