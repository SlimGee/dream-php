<?php
namespace Dream\Database\ActiveRecord;

/**
 *
 */
class Proxy extends RowSet
{
    public static $identity;
    public static $class;
    public static $clause;

    public static function create(array $data)
    {
        $data[self::$clause] = self::$identity;
        return self::$class::create($data);
    }
}
