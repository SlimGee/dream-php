<?php
namespace Lead\Components;
use Lead\IExpression;
/**
 *
 */
class Html implements IExpression
{
    private $html;

    function __construct(string $html)
    {
        $this->html = trim($html,"\n");
    }

    public function evaluate()
    {
        echo $this->html;
    }
}
