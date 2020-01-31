<?php

namespace Dream\Container;

use Dream\Container\NotFoundException;
use Dream\Container\ContainerException;
use Psr\Container\ContainerInterface;

/**
 *
 */
class Container implements ContainerInterface
{
    /**
     *
     */
    protected $entries = [];

    protected $instances = [];

    protected $rules = [];

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
        if (!$this->has($id)) {
            $this->set($id);
        }
        if ($this->entries[$id] instanceof \Closure || is_callable($this->entries[$id])) {
            return $this->entries[$id]($this);
        }
        if (isset($this->rules['shared']) && in_array($id, $this->rules['shared'])) {
            return $this->singleton($id);
        }
        return $this->resolve($id);
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

    public function set($abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        $this->entries[$abstract] = $concrete;
    }

    /**
     * Resolves a class name and creates its instance with dependencies
     * @param string $class The class to resolve
     * @return object The resolved instance
     * @throws Psr\Container\ContainerExceptionInterface when class cannot be instantiated
     */
    public function resolve($alias)
    {
        $reflector = $this->getReflector($alias);
        $constructor = $reflector->getConstructor();
        if ($reflector->isInterface()) {
            return $this->resolveInterface($reflector);
        }
        if (!$reflector->isInstantiable()) {
            throw new ContainerException(
                "Cannot inject {$reflector->getName()} to {$class} because it cannot be instantiated"
            );
        }
        if (null === $constructor) {
            return $reflector->newInstance();
        }
        $args = $this->getArguments($alias, $constructor);
        return $reflector->newInstanceArgs($args);
    }

    public function singleton($alias)
    {
        if (!isset($this->instances[$alias])) {
            $this->instances[$alias] = $this->resolve(
                $this->entries[$alias]
            );
        }
        return $this->instances[$alias];
    }

    public function getReflector($alias)
    {
        $class = $this->entries[$alias];
        try {
            return (new \ReflectionClass($class));
        } catch (\ReflectionException $e) {
            throw new NotFoundException(
                $e->getMessage(), $e->getCode()
            );
        }
    }

    /**
     * Get the constructor arguments of a class
     * @param ReflectionMethod $constructor The constructor
     * @return array The arguments
     */
    public function getArguments($alias, \ReflectionMethod $constructor)
    {
        $args = [];
        $params = $constructor->getParameters();
        foreach ($params as $param) {
            if (null !== $param->getClass()) {
                $args[] = $this->get(
                    $param->getClass()->getName()
                );
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } elseif (isset($this->rules[$alias][$param->getName()])) {
                $args[] = $this->rules[$alias][
                    $param->getName()
                ];
            }
        }
        return $args;
    }

    /**
     * Returns instance implementig the type hinted interface
     * @param \ReflectionClass $reflector The interface Reflector
     * @return object Instance implementig the interface
     * @throws Psr\Container\NotFoundExceptionInterface
     */
    public function resolveInterface(\ReflectionClass $reflector)
    {
        $classes = get_declared_classes();
        foreach ($classes as $class) {
            $rf = new \ReflectionClass($class);
            if ($rf->implementsInterface($reflector->getName())) {
                return $this->get($rf->getName());
            }
        }
        throw new NotFoundException(
            "Class {$reflector->getName()} not found", 1
        );
    }

    public function configure(array $config)
    {
        $this->rules = array_merge($this->rules,$config);
        return $this;
    }
}
