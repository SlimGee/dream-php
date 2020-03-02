<?php

namespace Dream\Database\Migration;

/**
 *
 */
class Column
{
    private $name;

    private $attributes = [];

    private static $keys = [];

    private $type;

    function __construct(string $name)
    {
        $this->name = $name;
    }

    public function string()
    {
        $this->type = "VARCHAR(150)";
        return $this;
    }

    public function int()
    {
        $this->type = "INT";
        return $this;
    }

    public function text()
    {
        $this->type = "TEXT";
        return $this;
    }

    public function bool()
    {
        $this->type = "INT(1)";
        return $this;
    }

    public function decimal()
    {
        $this->type = "DECIMAL";
        return $this;
    }

    public function date()
    {
        $this->type = "DATE";
        return $this;
    }

    public function time()
    {
        $this->type = "TIME";
        return $this;
    }

    public function timestamp()
    {
        $this->type = "TIMESTAMP";
        return $this;
    }

    public function null($null = true)
    {
        if (!$null) {
            $this->attributes[] = "NOT NULL";
        }
        return $this;
    }

    public function default($literal)
    {
        $this->attributes[] = "DEFAULT {$literal}";
        return $this;
    }

    public function primary_key()
    {
        $this->attributes[] = "PRIMARY KEY";
        return $this;
    }

    public function references($table, $column = 'id')
    {
        self::$keys[] = "FOREIGN KEY ({$this->name}) REFERENCES {$table}({$column}) ON UPDATE CASCADE";
        return $this;
    }

    public function uniq()
    {
        $this->attributes[] = 'UNIQUE';
    }

    public function eval()
    {
        return sprintf("`%s` %s %s", $this->name, $this->type, implode(' ', $this->attributes));
    }

    public static function keys()
    {
        return implode(',', self::$keys);
    }
}
