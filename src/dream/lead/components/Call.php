<?php
namespace Lead\Components;
use Lead\IExpression;
use Lead\Exceptions\UndefinedMethodException;

/**
 *
 */
class Call implements IExpression
{
    private $symbol;

    private $params;

    function __construct($symbol,$params)
    {
        $this->symbol = $symbol;
        $this->params = $params;
    }

    public function evaluate()
    {
        $args = [];
        foreach ($this->params as $value) {
            $args[] = $value->evaluate();
        }
        if (!is_callable($this->symbol->getValue()))
        {
            throw new UndefinedMethodException($this->symbol->name());
        }
        return call_user_func_array($this->symbol->getValue(),$args);
    }
}
