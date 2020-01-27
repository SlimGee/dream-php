<?php
namespace Lead\Components;
/**
 *
 */
class Division extends BinaryOperator
{
  public function evaluate()
  {
    return $this->left->evaluate() / $this->right->evaluate();
  }
}
