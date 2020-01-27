<?php
namespace Lead\Exceptions;
/**
 *
 */
class UndefinedMethodException extends \Exception
{
    function __construct($name)
    {
        parent::__construct("Undefined method " . $name,1);
    }
}
