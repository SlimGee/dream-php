<?php

namespace Dream\Http\Routes;

/**
 *
 */
class RouteParam
{
  private $controller;

  private $action;

  private $name;

  public $params;

  public function __construct(array $route)
  {
    foreach ($route as $key => $value) {
      $this->$key = $value;
    }
  }

  public function __get($prop)
  {
    return $this->$prop;
  }
}
