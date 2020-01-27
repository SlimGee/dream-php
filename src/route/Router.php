<?php
namespace Dream\Route;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 *
 */
class Router
{
    /**
     * get routes
     * @var Dream\Route\Route[]
     */
    public static $get;

    /**
     * post routes
     * @var Dream\Route\Route[]
     */
    private $post;

    /**
     * put routes
     * @var Dream\Route\Route[]
     */
    private $put;

    /**
     * delete routes
     * @var Dream\Route\Route[]
     */
    private $delete;

    /**
     * patch
     * @var Dream\Route\Route[]
     */
    private $patch;

    /**
     * current route
     * @var Dream\Route\Route[]
     */
    private $currentRoute;

    /**
     *
     */
    private $handler;

    public function __construct(RequestHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * matches an incoming request to a specific route
     * @param Psr\Http\Message\ServerRequestInterface
     * @return bool whether a route matches
     */
    public function match(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();
        $routes = $this->getRoutes($request->getMethod());

        foreach ($routes as $path => $route) {
            if (match($uri, $this->normalize($path))) {
                $this->currentRoute = $route;
            }
        }

        if (!isset($this->currentRoute)) {
            return false;
        }
        $this->request = $request->withAttribute('controller',$this->currentRoute->controller)
                                 ->withAttribute('action',$this->currentRoute->action);
        return true;
    }

    /**
     * Gets the action dispatcher
     * @return Psr\Http\Message\ServerRequestInterface The action dispatcher
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Gets the routes ascociated the request method
     * @return Dream\Routes\Route[] The routes
     */
    public function getRoutes($method)
    {
        return self::${strtolower($method)};
    }

    /**
     * Normalise a path for regex
     * @param string $string the path
     * @return string The normalized path
     */
    private function normalize($string)
    {
        $parts = explode('/',$uri);
        $ret = [];

        foreach ($parts as $key => $value){
            $elem = preg_replace('/:\w+/','\\w+',$value);
            if ($key < sizeof($parts)-1) {
                $elem = $elem . '\\';
            }
            $ret[] = $elem;
        }
        return implode('/',$ret);
    }
}
