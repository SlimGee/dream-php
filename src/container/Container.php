<?php

namespace Dream\Container;

use Psr\Container\ContainerInterface;

/**
 *
 */
class Container implements ContainerInterface
{
    private $entries = [];
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!isset($this->entries[$id])) {
            throw new \Exception("Not found in container", 1);
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->entries[$id]);
    }

    public function register($key,$value)
    {
        $this->entries[$key] = $value;
    }
}
