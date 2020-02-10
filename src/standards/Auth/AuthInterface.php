<?php

namespace Dream\Standards\Auth;

/**
 *
 */
interface AuthInterface
{
    /**
     * Attempts to authenticate user on given request
     * @param Psr\Http\Message\ServerRequestInterface
     * @return bool
     */
    public function authenticate(ServerRequestInterface $request);

    /**
     * registers an auth service
     * @param Dream\Standards\Auth\AuthServiceInterface;
     */
    public function use(AuthServiceInterface $service);

    /**
     * Whether user is logged
     */
    public function hasIdentity();

    /**
     * Returns the currently authenticated user
     */
    public function user();

    /**
     * Logges the user out by deleting the session
     */
    public function logout();
}
