<?php
namespace Dream\Patterns\Factory;
/**
 * Helper Factory
 */
class HelperFactory
{
    public static function load($controller)
    {
        $class = "\\App\\Helpers\\" . $controller . "Helper";
        if (class_exists($class)){
            return new $class();
        }
        return new \App\Helpers\ApplicationHelper;
    }
}
