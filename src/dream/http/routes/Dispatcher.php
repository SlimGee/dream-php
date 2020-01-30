<?php
namespace Dream\Http\Routes;
use Dream\Exceptions\UndefinedHookException;
class Dispatcher
{
  /**
   * dream action dispatcher
   * dispatches a request
   */
  public static function dispatch(Route $route)
  {
    $controller = '\App\Controllers\\' . ucfirst($route->controller);

    $action = $route->action;

    \Dream\Registry::set(
      'view_helper',
      \Dream\Patterns\Factory\HelperFactory::load($route->controller)
    );

    \Dream\Views\View::register_methods(\Dream\Registry::get('view_helper'));

    $controller = new $controller();

    \Dream\Registry::get('view_helper')->controller = $controller;

    $controller->params = new \Dream\Http\Params($route->params);
    $controller->params['controller'] = $route->controller;
    $controller->params['action'] = $route->action;
    $controller->flush = \Dream\Registry::get('flush');
    \Dream\Registry::set('controller',$controller);

    //controller hooks function
    // HACK: added this so that some actions can be done before others and after
    $hook = function ($at) use ($controller,$action){
        $meta = get_class_meta(get_class($controller),$action);
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

    $hook('@before');
    $controller->$action();
    $hook('@after');
  }
}
