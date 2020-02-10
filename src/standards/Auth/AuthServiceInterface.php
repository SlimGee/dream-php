<?php

namespace Dream\Standards\Auth;

/**
 *
 */
interface AuthServiceInterface
{
    public function attempt(AuthInterface $auth, $cred, $blueprint);
}
