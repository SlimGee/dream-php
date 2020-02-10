<?php

namespace Dream\Auth\Storage;

use Dream\Standards\Auth\StorageInterface;

/**
 *
 */
class SessionStorage implements StorageInterface
{
    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return app()->registry()->get('session')::has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        return app()->registry()->get('session')::set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return app()->registry()->get('session')::get($key);
    }

    public function erase($key)
    {
        return app()->registry()->get('session')::erase($key);
    }
}
