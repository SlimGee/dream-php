<?php
namespace Lead;
/**
 *
 */
class Evaluator
{

  private $parser;

  function __construct(Parser $parser)
  {
    $this->parser = $parser;
  }

  public function evaluate()
  {
    echo "evaluating\n";
  }
}
