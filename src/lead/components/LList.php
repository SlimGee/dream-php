<?php
namespace Lead\Components;
use Lead\IExpression;
use Lead\Components\LNull;
/**
 *
 */
class LList implements IExpression
{
    protected $array;

    function __construct($array)
    {
        $this->array = $array;
    }

    public function evaluate()
    {
        return $this;
    }
    public function at($index)
    {
        return isset($this->array[$index]) ? $this->array[$index] : new LNull();
    }

    public function each(callable $handler)
    {
        foreach ($this->array as $value) {
            call_user_func_array($handler,[$value,$this]);
        }
    }

    public function next()
    {
        return next($this->array);
    }

    public function getValue()
    {
        return $this->array;
    }
}
