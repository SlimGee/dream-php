<?php

namespace Dream\Database\Migration;

/**
 * The Table class that represents a typical Database table
 * This is part of the Dream Framework
 */
class Table
{
    /**
     * The table name
     * @var string
     */
    private $name;

    /**
     * The table columns
     * @var array
     */
    private $columns = [];

    /**
     * Initializes tabe with name
     * @param string $name The table name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the name of the table
     * @return string The name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Generates the sql from the called methods
     * @return string The sql
     */
    public function sql()
    {
        $sql = '';
        foreach ($this->columns as $column) {
            $sql .= $column->eval() . ', ';
        }
        return rtrim($sql . Column::keys(), ', ');
    }

    public function timestamps()
    {
        $this->columns[] = (new Column('created_at'))->timestamp()->default('NOW()');
        $this->columns[] = (new Column('updated_at'))->timestamp()->default('NOW()');
    }

    /**
     * Checks if a method is defined in the Column class and calls it
     * @param string $method Invoked method
     * @param mixed[] $args Args passed to the method
     * @throws \ErrorException if method is not defined
     */
    public function __call($method, $args)
    {
        $reflector = new \ReflectionClass(Column::class);
        if ($reflector->hasMethod($method)) {
            $this->columns[] = ($reflector->newInstanceArgs($args))->$method();
            return $this->columns[count($this->columns) - 1];
        }
        throw new \ErrorException(
            "Call to undefined method " . get_called_class() . '::' . $method . "()"
        );
    }
}
