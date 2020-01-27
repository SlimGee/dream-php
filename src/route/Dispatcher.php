<?php
namespace Dream\Route;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Dream\Http\Response;

/**
 *
 */
class Dispatcher implements RequestHandlerInterface
{
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $controller = $request->getAttribute('controller');
        $action = $request->getAttribute('action');
        \Dream\Registry::set('action_view',$controller . '/' . $action);

        \Dream\Registry::set(
            'view_helper',
            \Dream\Patterns\Factory\HelperFactory::load($controller)
        );
        $controller = '\App\Http\Controllers\\' . ucfirst($controller);


        $controller = new $controller();

        \Dream\Registry::get('view_helper')->controller = $controller;

        $controller->params = new \Dream\Http\Params([]);
        $controller->params['controller'] = $controller;
        $controller->params['action'] = $action;
        $controller->flush = \Dream\Registry::get('flush');
        \Dream\Registry::set('controller',$controller);

        return $controller->handle($request->withoutAttribute('controller'));
    }
}
