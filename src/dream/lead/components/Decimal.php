<?php
namespace Lead\Components;
/**
 *
 */
class Decimal extends Number
{
  private $value;

  public function __construct($value)
  {
    $this->value = (float)$value;
  }

  public function evaluate()
  {
    return $this->value;
  }
}
