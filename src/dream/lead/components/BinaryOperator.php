<?php
namespace Lead\Components;

use Lead\IExpression;

abstract class BinaryOperator implements IExpression
{
  protected $left;

  protected $right;

  public function __construct($left, $right)
  {
    $this->left = $left;
    $this->right = $right;
  }
}
