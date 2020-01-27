<?php
namespace Lead\Components;
/**
 *
 */
class Multiplication extends BinaryOperator
{
  public function evaluate()
  {
    return ($this->left->evaluate() * $this->right->evaluate());
  }
}
