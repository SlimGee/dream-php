<?php
namespace Dream\Http\Controllers;
use Dream\Registry;
use Dream\Http\Sessions\Session;
use Dream\Views\View;
use Lead\Components\Variable;

/**
 *
 */
class Controller
{
    use \Dream\Http\Controllers\Concerns\Forgery;
  /**
   * dream controller
   * Copyright (c) 2020 Dream framework
   * @author Given
   * @package dream
   * @version 0.0.1
   */

  /**
   * all passed parameters
   */
  public $params;

  public $will_render = true;

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
   * render the application view
   * @return void;
   */
  public function render()
  {
      $this->set_view_vars();
      $route = Registry::get('router')->route;
      Registry::set('action_view',$route->controller . '/' . $route->action);
      $this->view = new View('application');
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
      $this->set_back_link();
      if ($this->will_render) {
          $this->render();
      }
  }

  public function set_view_vars()
  {
      foreach ($this as $key => $value) {
          $var = new Variable($key);
          $var->setValue($value);
      }
  }
}
