<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LString implements IExpression
{
    function __construct($value)
    {
        $this->value = $value;
    }

    public function evaluate()
    {
        return $this->value;
    }
}
