<?php

namespace Dream\Standards;

/**
 *
 */
interface Authenticable
{
    /**
     * Retrieves the authentication identifier name
     * @return string The authentication id name
     */
    public function getAuthIdName();

    /**
     * Retrieves the authentication identifier
     * @return string The identifier
     */
    public function getAuthId();

    /**
     * Retrieves the user supplied password
     * @return string The password
     */
    public function getAuthPassword();

    /**
     * Retrieves the remember token
     * @return string The token
     */
    public function getRememberToken();

    /**
     * Sets the remember me token
     */
    public function setRemberToken();

    /**
     * Retrieves the remember me token name
     * @return string The token name
     */
    public function getRememberTokenName();
}
