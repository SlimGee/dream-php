<?php
namespace Lead;
/**
 *
 */
class Variables
{
    private static $variables = [];

    public static function set($name,$value,$scope = NULL)
    {
        if ($scope !== NULL)
        {
            self::$variables[$scope][$name] = $value;
        }
        else
        {
            self::$variables['global'][$name] = $value;
        }
    }

    public static function declared($name,$scope = NULL)
    {
        if ($scope !== NULL)
        {
            return (isset(self::$variables[$scope][$name])) ? true : false ;
        }
        return (isset(self::$variables['global'][$name])) ? true : false ;
    }

    public static function get($value,$scope = NULL)
    {
        if ($scope !==NULL)
        {
            return self::$variables[$scope][$value];
        }
        return isset(self::$variables['global'][$value]) ? self::$variables['global'][$value] : NULL;
    }

    public static function undeclare($name,$scope = NULL)
    {
        if ($scope !==NULL)
        {
            unset(self::$variables[$scope][$name]);
        }
        else
        {
            unset(self::$variables['global'][$name]);
        }
    }
}
