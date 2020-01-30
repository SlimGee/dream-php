<?php

namespace Dream\Http\Controllers;

use Dream\Registry;
use Dream\Http\Response;
use Dream\Http\TextStream;
use Dream\Http\Sessions\Session;
use Dream\Views\View;
use Dream\Exceptions\UndefinedHookException;
use Lead\Components\Variable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * The base controller
 */
class Controller
{
    use \Dream\Http\Controllers\Concerns\Forgery;
    /**
     * all passed parameters
     */
    public $params = [];

    public $willRender = true;

    /**
     * current layout
     */
    private $layout;


    /**
     * application constructor
     */
    public function __construct()
    {
        $this->protect_from_foregery();
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function invokeAction(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getAttribute('route')->action;
        //controller hooks function
        // HACK: added this so that some actions can be done before others and after
        $hook = function ($at) use ($action){

            $meta = get_class_meta(get_class($this),$action);
            if (!isset($meta[$at])) {
                return;
            }
            $meta = $meta[$at];
            foreach ($meta as $method) {
                if (is_callable([$this,$method])) {
                    call_user_func([$this,$method]);
                }
                elseif(is_callable($method)) {
                    call_user_func($method);
                }
                else {
                    throw new UndefinedHookException($method,$action);
                }
            }
        };

        $hook('@before');
        $this->$action();
        $hook('@after');

        ob_start();
        return $this->invokeView($request);
    }

    /**
     * Invokes the View and generate appropriate response
     */
    public function invokeView(ServerRequestInterface $request)
    {
        $accept = $request->getHeaderLine('Accept')[0];
        if ($accept && match($accept, '^application/([^+\s]+\+)?json')) {
            return (new Response(200))->withBody(new TextStream(json_encode([
                                            'data' => [
                                                'status' => '200',
                                                'details' => 'JSON api comming soon!'
                                            ]
                                        ])))
                                      ->withHeader('Content-Type','application/json');
        }

        if ($accept) {
            foreach ($this as $key => $value) {
                $var = new Variable($key);
                $var->setValue($value);
            }
            if ($this->willRender) {
                $view = new View('application');
            }
            $content = ob_get_contents();
            ob_end_clean();
            return (new Response(200))->withBody(new TextStream($content))
                                      ->withHeader('Content-Type','text/html');
        }

        return (new Response(400));
    }
}
