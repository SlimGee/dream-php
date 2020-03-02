<?php

namespace Dream\Database;

/**
 *
 */
class Schema
{
    private static $version;

    public static function version($value=null)
    {
        if (null == $value) {
            return self::$version;
        }
        self::$version = (int)$value;
        return self::$version;
    }
}
