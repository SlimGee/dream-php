<?php
namespace Lead\Components;
/**
 *
 */
class Addition extends BinaryOperator
{
  public function evaluate()
  {
      if (!is_numeric($this->left->evaluate())){
          return $this->left->evaluate() . $this->right->evaluate();
      }
    return $this->left->evaluate() + $this->right->evaluate();
  }
}
