<?php

namespace Dream\Standards\Auth;

/**
 *
 */
interface AuthServiceInterface
{
    public function attempt($cred, $remember = null);

    public function byRemember(string $token);

    public function user();

    public function getId();
}
