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
    public static $post;

    /**
     * put routes
     * @var Dream\Route\Route[]
     */
    public static $put;

    /**
     * delete routes
     * @var Dream\Route\Route[]
     */
    public static $delete;

    /**
     * patch
     * @var Dream\Route\Route[]
     */
    public static $patch;

    /**
     * current route
     * @var Dream\Route\Route[]
     */
    private $currentRoute;

    /**
     *
     */
    private $handler;

    /**
     * constructor
     * @param Psr\Http\Server\RequestHandlerInterface The Dispatcher
     */
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
            if ($this->checkStructure($path,$uri)) {
                $this->currentRoute = $route;
                $this->currentRoute->params = $this->getParams($path,$uri);
                break;
            }
        }

        if (!isset($this->currentRoute)) {
            return false;
        }
        $this->request = $request->withAttribute('route',$this->currentRoute);

        return true;
    }

    public function toArray($uri1, $uri2)
    {
        $a = array_values(
            array_filter(explode('/',$uri1),function ($part){
                return $part !== '';
            })
        );
        $b = array_values(
            array_filter(explode('/',$uri2),function ($part){
                return $part !== '';
            })
        );

        return [$a, $b];
    }

    /**
     * Analyzes the structure of 2 given uris to see if they match
     * @param string $saved The user defined uri path
     * @param string $input The input uri from the request
     * @return bool whether the uris match
     */
    public function checkStructure($saved,$input)
    {
        list($saved, $input) = $this->toArray($saved, $input);
        //if the size is not equal they obviously don't match
        if (sizeof($saved) !== sizeof($input)) {
            return false;
        }

        foreach ($saved as $key => $value) {
            //if it is not a dynamic param and the elements are not the same
            //or if it is a dynamic param and the element is a query string
            // the uris do not match
            if ($value[0] !== ':' && $value !== $input[$key] || $value[0] === ':' && $input[$key] === '?') {
                return false;
            }
        }
        return true;
    }

    /**
     * Gets the uri passed parameters from two given uri strings
     * @param string $saved the user defined uri path
     * @param string $input  the input uri from the request
     * @return array The params
     */
    public function getParams($saved,$input)
    {
        $params = [];
        list($saved, $input) = $this->toArray($saved, $input);
        foreach ($saved as $key => $value) {
            if ($value[0] === ':') {
                $params[substr($value,1)] = $input[$key];
            }
        }
        return $params;
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
     * Gets the current request but modified
     * @return Psr\Http\Message\ServerRequestInterface;
     */
    public function getRequest()
    {
        return $this->request;
    }
}
