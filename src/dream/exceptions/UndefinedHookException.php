<?php
namespace Dream\Exceptions;
/**
 *
 */
class UndefinedHookException extends \Exception
{
    public function __construct($hook,$action)
    {
        parent::__construct("Undefined hook {$hook} called on action {$action}.",1);
    }
}
