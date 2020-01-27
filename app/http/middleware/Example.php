<?php

namespace App\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Dream\Http\Response;
use Dream\Http\TextStream;

/**
 *
 */
class Example implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(Request $request, Handler $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withStatus(200)
                        ->withBody(new TextStream(json_encode([
                            'user' => 'given'
                        ])))
                        ->withHeader('Content-Type','application/json');
    }
}
