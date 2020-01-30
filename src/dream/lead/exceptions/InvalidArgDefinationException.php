<?php
namespace Lead\Exceptions;
/**
 *
 */
class InvalidArgDefinationException extends \Exception
{
    function __construct($a)
    {
        parent::__construct("Arguments can only be variables when defining a function. This is what i got " . $a);
    }
}
