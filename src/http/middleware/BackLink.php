<?php

namespace Dream\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Dream\Registry;
use Dream\Http\Response;
use Dream\Http\TextStream;
use Dream\Http\Sessions\Session;

/**
 *
 */
class BackLink implements MiddlewareInterface
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
        $back = Session::has('back_link') ? Session::get('back_link') : $request->getUri()->__toString();
        Registry::set('back_link',$back);
        Session::set('back_link',$request->getUri()->__toString());
        $response = $handler->handle($request);
        return $response;
    }
}
