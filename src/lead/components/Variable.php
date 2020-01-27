<?php
namespace Lead\Components;

use Lead\IExpression;
use Lead\Variables;
use Lead\Exceptions\UndeclaredVariableException;

class Variable implements IExpression
{
  private $value;

  private $name;

  private $params;

  public function __construct($name,$value = NULL)
  {
    $this->value = $value;
    $this->name = $name;
    if(Variables::declared($name) && $value == NULL)
    {
        $this->value = Variables::get($name)->getValue();
    }
    Variables::set($this->name,$this);
  }

  public function evaluate()
  {
      if ($this->value == NULL)
      {
          $this->value = 'undefined';
      }
      if(Variables::declared($this->name))
      {
          if (is_callable($this->value))
          {
              if ($this->params == NULL) {
                  $this->params = [];
              }
              return call_user_func_array($this->value,$this->params);
          }
          return $this->value;
      }
      throw new UndeclaredVariableException($this->name);
  }

  public function setParams($params)
  {
      $this->params[] = $params->evaluate();
  }

  public function setValue($value)
  {
      $this->value = $value;
      Variables::set($this->name,$this);
  }

  public function getValue()
  {
      return $this->value;
  }

  public function name()
  {
      return $this->name;
  }
}
