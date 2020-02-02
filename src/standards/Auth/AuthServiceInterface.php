<?php

namespace Dream\Standards\Auth;

/**
 *
 */
interface AuthServiceInterface
{
    public function attempt($cred, $remember = null);
}
