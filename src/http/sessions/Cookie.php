<?php
namespace Dream\Http\Sessions;

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

    public static function check($key)
    {
      r eturn isset($_COOKIE[$key]);
    }
}
