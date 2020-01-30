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
        $route = $request->getAttribute('route');

        \Dream\Registry::set('action_view',$route->controller . '/' . $route->action);

        \Dream\Registry::set(
            'view_helper',
            \Dream\Patterns\Factory\HelperFactory::load($route->controller)
        );
        $controller = '\App\Http\Controllers\\' . ucfirst($route->controller);


        $controller = new $controller();

        \Dream\Registry::get('view_helper')->controller = $controller;
        \Dream\Views\View::register_methods(
            \Dream\Registry::get('view_helper')
        );
        $params = array_merge($this->getParams($request), $route->params);
        $controller->params = new \Dream\Http\Params($params);
        $controller->params['controller'] = $route->controller;
        $controller->params['action'] = $route->action;
        $controller->flush = \Dream\Registry::get('flush');
        \Dream\Registry::set('controller',$controller);

        return $controller->invokeAction($request);
    }

    /**
     * Gets the params from incoming request
     * The include uploaded files
     * if the method is get or post or content type is json it will return the params
     * @param Psr\Http\Message\ServerRequestInterface $request The incoming request
     * @return array The params
     */
    public function getParams(ServerRequestInterface $request)
    {
        if (is_array($request->getParsedBody())) {
            return array_merge($request->getParsedBody() ?? [], $request->getUploadedFiles() ?? []);
        }
        return [];
    }
}
