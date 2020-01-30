<?php
namespace Lead\Components;

use Lead\IExpression;
use Lead\Exceptions\UndefinedMethodException;
/**
 *
 */
class PropertyFetch extends BinaryOperator
{
    private $params = [];

    public function evaluate()
    {
        $var = $this->left->evaluate();
        if (is_object($var))
        {
            if (is_callable([$var,$this->right]))
            {
                $args = [];
                if (count($this->params) > 0)
                {
                    foreach ($this->params as $value)
                    {
                        $args[] = $value->evaluate();
                    }
                }
                return call_user_func_array([$var,$this->right],$args);
            }
            elseif (property_exists($var,$this->right))
            {
                return $var->{$this->right} ?? NULL;
            }
            elseif (is_a($var,'llist'))
            {
                return $var->at($this->right)->evaluate();
            }
            throw new UndefinedMethodException($this->left->name() . "::" .$this->right);
        }
        return $var[$this->right];
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
