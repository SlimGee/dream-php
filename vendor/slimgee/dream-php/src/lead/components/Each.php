<?php
namespace Lead\Components;

use Lead\IExpression;
use Lead\Variables;
/**
 *
 */
class Each implements IExpression
{
    private $block;

    private $collection;

    private $alias;

    public function __construct($collection,$alias,$block)
    {
        $this->collection = $collection;
        $this->block = $block;
        $this->alias = $alias;
    }
    public function evaluate()
    {
        $collection = $this->collection->evaluate();
        if (is_a($collection,'llist'))
        {
            $collection->each(function ($item){
                Variables::get($this->alias)->setValue($item);
                $this->block->evaluate();
            });
        }
        else
        {
            foreach ($collection as $value)
            {
                Variables::get($this->alias)->setValue($value);
                $this->block->evaluate();
            }
        }
    }
}
