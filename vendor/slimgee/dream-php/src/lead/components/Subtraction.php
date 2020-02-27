<?php
namespace Lead\Components;
/**
 *
 */
class Subtraction extends BinaryOperator
{
  public function evaluate()
  {
    return $this->left->evaluate() - $this->right->evaluate();
  }
}
