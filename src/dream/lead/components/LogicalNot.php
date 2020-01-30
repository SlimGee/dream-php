<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LogicalNot
{
    private $right;

    public function __construct($right)
    {
        $this->right = $right;
    }

    public function evaluate()
    {
        return !$this->right->evaluate();
    }
}
