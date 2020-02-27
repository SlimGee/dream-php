<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class Condition implements IExpression
{
    private $if;

    private $then;

    private $else;

    private $elseif;

    public function __construct($if,$then,$else = NULL,$elseif = NULL )
    {
        $this->if = $if;
        $this->else = $else;
        $this->then = $then;
        $this->elseif = $elseif;
    }

    public function evaluate()
    {
        if ($this->if->evaluate())
        {
            return $this->then->evaluate();
        }
        elseif ($this->elseif !== NULL)
        {
            return $this->elseif->evaluate();
        }
        elseif($this->else !== NULL)
        {
            return $this->else->evaluate();
        }
    }
}
