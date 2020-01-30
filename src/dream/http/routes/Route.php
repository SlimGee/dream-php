<?php
namespace Dream\Http\Routes;

/**
 * Route class
 */
class Route{

  /**
   * the controller
   */
  public $controller;

  /**
   * method to be called
   */
  public $action;

  /**
   * params passed
   */
  public $params = [];

  /**
  * constructor
  * @param array route options
  * @return void;
  */
  public function __construct($route = [])
  {
    foreach ($route as $key => $value) {
      $this->$key = $value;
    }
  }

  /**
  * set get routes
  * @param string uri pattern
  * @param array route options
  * @return void;
  */
  public static function get(string $path)
  {
    Router::$getRoutes[$path] = new static([]);
    return Router::$getRoutes[$path];
  }

  public function to($ca)
  {
      $ca = explode('#',$ca);
      $this->controller = $ca[0];
      $this->action = $ca[1];
      return $this;
  }

  public function name($name)
  {
      $this->name = $name;
  }

  /**
  * set post routes
  * @param string uri pattern
  * @param array route options
  * @return void;
  */
  public static function post(string $path)
  {
    Router::$postRoutes[$path] = new static([]);
    return Router::$postRoutes[$path];
  }
}
