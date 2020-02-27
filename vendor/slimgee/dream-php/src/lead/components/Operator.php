<?php
namespace Lead\Components;
use Lead\IExpression;

/**
 *
 */
class Operator implements IExpression
{
    private $operators = [
        '+'   => 'Addition',
        '-'   => 'Subtraction',
        '*'   => 'Multiplication',
        '/'   => 'Division',
        '=='  => 'EqualCompare',
        '<'   => 'LessThanCompare',
        '>'   => 'GreaterThanCompare',
        '!==' => 'LogicalNotEqual',
        '&&'  => 'LogicalAnd',
        '||'  => 'LogicalOr',
        '>='  => 'GreaterThanOrEqual',
        '<='  => 'LessThanOrEqual'
    ];

    private $operator;

    function __construct($left,$right,$operator)
    {
        $class = "Lead\\Components\\" . $this->operators[$operator];
        $this->operator = new $class($left,$right);
    }

    public function evaluate()
    {
        return $this->operator->evaluate();
    }
}
