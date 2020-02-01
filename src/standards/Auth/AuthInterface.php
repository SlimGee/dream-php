<?php

namespace Dream\Auth;

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
     * @param Dream\Auth\AuthServiceInterface;
     */
    public function use(AuthServiceInterface $service);
}
