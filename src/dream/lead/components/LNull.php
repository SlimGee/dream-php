<?php
namespace Lead\Components;

use Lead\IExpression;

/**
 *
 */
class LNull implements IExpression
{
    public function evaluate()
    {
        return null;
    }
}
