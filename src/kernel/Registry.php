<?php

namespace Dream\Kernel;

/**
 *
 */
class Registry
{
    /**
     * Registry items
     * @var object[]
     */
    protected $items = [];

    /**
     * Retrieves an item from the registry
     * @param string $key The offset of the item
     * @return mixed The item
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \Exception("Item {$key} not in registry", 1);
        }
        return $this->items[$key];
    }

    /**
     * Registers an item to the registry
     * @param string $key The offset of the item
     * @param mixed $value The item to store
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Returns true if the item is in registry
     * @param string $key The offset of the item in the registry
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }
}
