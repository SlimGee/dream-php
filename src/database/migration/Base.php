<?php

namespace Dream\Database\Migration;

/**
 *
 */
class Base
{
    private const CREATE_TABLE = "CREATE TABLE %s ( %s );";
    private const DROP_TABLE = "DROP TABLE %s;";
    private const DROP_COLUMN = "ALTER TABLE %s DROP COLUMN %s;";
    private const ADD_COLUMN = "ALTER TABLE %s ADD COLUMN %s";
    private const MODIFY_COLUMN = "ALTER TABLE %s MODIFY %s";
    private const CHANGE_COLUMN = "ALTER TABLE %s CHANGE %s %s";
    private const RENAME_TABLE = "ALTER TABLE %s RENAME TO %s";

    public function create_table(string $name, callable $func)
    {
        $table = call_user_func_array($func, [new Table($name)]);
        $sql = sprintf(self::CREATE_TABLE, $table->name(), $table->sql());
        return app()->db()->query($sql);
    }

    public function drop_table(string $name)
    {
        return app()->db()->query(
            sprintf(self::DROP_TABLE, $name)
        );
    }

    public function drop_column(string $column, string $table)
    {
        return app()->db()->query(
            sprintf(self::DROP_COLUMN, $table, $column)
        );
    }

    public function add_column(string $table, callable $callabe)
    {
        $table = call_user_func_array($callabe, [new Table($table)]);
        return app()->db()->query(
            sprintf(self::ADD_COLUMN, $table->name(), $table->sql())
        );
    }

    public function modify_column(string $table, callable $callabe)
    {
        $table = call_user_func_array($callabe, [new Table($table)]);
        return app()->db()->query(
            sprintf(self::MODIFY_COLUMN, $table->name(), $table->sql())
        );
    }

    public function change_column(string $table, string $column, callable $callabe)
    {
        $table = call_user_func_array($callabe, [new Table($table)]);
        return app()->db()->query(
            sprintf(self::CHANGE_COLUMN, $table->name(), $column, $table->sql())
        );
    }

    public function rename(string $old, string $new)
    {
        return app()->db()->query(
            sprintf(self::RENAME_TABLE, $old, $new)
        );
    }
}
