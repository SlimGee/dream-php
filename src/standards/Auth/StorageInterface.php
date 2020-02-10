<?php

namespace Dream\Standards\Auth;

/**
 *
 */
interface StorageInterface
{
    /**
     * Checks if a supplied key is in storage
     * @param string $key The key to look for
     * @return bool
     */
    public function has($key);

    /**
     * Sets certain entry in storage
     * @param string $key The key index
     * @param string $value The value to set
     */
    public function set($key, $value);

    /**
     * Returns the value at supplied key index
     * @param string $key
     * @return mixed The value
     */
    public function get($key);
}
