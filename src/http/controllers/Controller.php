<?php

namespace Dream\Http\Controllers;

use Dream\Registry;
use Dream\Http\Sessions\Session;
use Dream\Views\View;
use Lead\Components\Variable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Dream\Http\Response;
use Dream\Http\TextStream;


/**
 * The base controller
 */
class Controller implements RequestHandlerInterface
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
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getAttribute('action');
        //controller hooks function
        // HACK: added this so that some actions can be done before others and after
        $hook = function ($at) use ($action){

            $meta = get_class_meta(get_class($this),$action);
            if (!isset($meta[$at])) {
                return;
            }
            $meta = $meta[$at];
            foreach ($meta as $method) {
                if (is_callable([$controller,$method])) {
                    call_user_func([$controller,$method]);
                }
                elseif(is_callable($method)) {
                    call_user_func($method);
                }
                else {
                    throw new UndefinedHookException($method,$action);
                }
            }
        };

        ob_start();
        $hook('@before');
        $this->$action();
        $hook('@after');

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

    public function set_back_link()
    {
        Session::set('back_link',Registry::get('back_link'));
        Registry::erase('back_link');
    }

    /**
     * class destructor
     */
    public function __destruct()
    {
        //$this->set_back_link();
    }
}
