<?php

namespace Dream\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Dream\Http\Response;
use Dream\Http\TextStream;

/**
 *
 */
class Routing implements MiddlewareInterface
{
    private $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(Request $request, Handler $handler): ResponseInterface
    {
        if ($this->router->match($request)) {
            return $this->router->getHandler()->handle($this->router->getRequest());
        }

        return $handler->handle($request);
    }
}
