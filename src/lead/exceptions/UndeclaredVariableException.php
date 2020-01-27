<?php
namespace Lead\Exceptions;
/**
 *
 */
class UndeclaredVariableException extends \Exception
{
    public function __construct($variable)
    {
        parent::__construct('Undeclared variable ' . $variable);
    }
}
