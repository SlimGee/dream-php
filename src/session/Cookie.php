<?php
namespace Dream\Session;

/**
 *
 */
class Cookie
{
    public static function set($key,$value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_COOKIE[$key];
    }

    public static function has($key)
    {
        return isset($_COOKIE[$key]);
    }
}
