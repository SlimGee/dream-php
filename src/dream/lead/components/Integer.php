<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class Integer extends Number
{
  private $value;

  public function __construct($value)
  {
    $this->value = (int)$value;
  }

  public function evaluate()
  {
    return $this->value;
  }
}
