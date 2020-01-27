<?php
namespace Dream\Http\Routes;
use Dream\Route\Router;

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

    \Dream\Route\Router::$get[$path] = new static([]);
    return \Dream\Route\Router::$get[$path];
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
      \Dream\Route\Router::$post[$path] = new static([]);
      return \Dream\Route\Router::$post[$path];
  }
}
