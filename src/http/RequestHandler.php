<?php

namespace Dream\Http;

use psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 *
 */
class RequestHandler implements RequestHandlerInterface
{
    private $middleware = [];

    private $fallbackHandler;

    public function __construct()
    {
        $this->fallbackHandler = new class implements RequestHandlerInterface{
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return (new Response(404))->withBody(new TextStream(''));
            }
        };
    }

    /**
     * Register middleware
     * @param Psr\Http\Server\MiddlewareInterface $middleware The middleware
     */
    public function add(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (0 === count($this->middleware)) {
            return $this->fallbackHandler->handle($request);
        }
        $middleware = array_shift($this->middleware);
        return $middleware->process($request, $this);
    }
}
