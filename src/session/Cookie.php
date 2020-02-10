<?php
namespace Dream\Session;

/**
 *
 */
class Cookie
{
    public static function set($key, $value, $exp)
    {
        setcookie($key, $value, time() + $exp);
    }

    public static function get($key)
    {
        return $_COOKIE[$key];
    }

    public static function has($key)
    {
        return isset($_COOKIE[$key]);
    }

    public static function erase($key)
    {
        return setcookie( $key, "", time()- 60, "/","", 0);
    }
}
