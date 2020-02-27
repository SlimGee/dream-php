<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class Comparison  extends BinaryOperator
{
    private $operators = [
        '==' => 'EqualCompare',
        '<'  => 'LessThanCompare',
        '>'  => 'GreaterThanCompare',
        '!==' => 'LogicalNotEqual',
        '&&'  => 'LogialAnd',
        '||'  => 'LogicalOr'
    ]
    function __construct(argument)
    {
        // code...
    }
}
