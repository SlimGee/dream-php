<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class LEcho implements IExpression
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function evaluate()
    {
        echo $this->data->evaluate();
    }
}
