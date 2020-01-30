<?php
namespace Dream\Http\Routes;
use Dream\Registry;
use Dream\Http\Request;
/**
 *
 */
class Router
{
  /**
   * stores all post routes
   */
  public static $postRoutes = [];

  /**
   * stores all get routes
   */
  public static $getRoutes = [];

  /**
   * stores the current route object
   */
  public $route;

  /**
   * route the current request
   * @return void;
   */
  public function route()
  {
    $url = Url::getUrl();
    foreach ($this->getCurrentRoutes() as $uri => $route)
    {
      $normalized = $this->normalize($uri);

      if ($normalized === '\/' && $url === '/')
      {
          $this->setCurrentRoute('root');

          // HACK: We will need that route for back links
          Registry::set('back_link',$url);
          Dispatcher::dispatch($this->route);
          return;
      }
      $url = rtrim($url,'/');
      if (preg_match("/^" . $normalized . "$/",$url) && $normalized !== '\/')
      {
        $this->setCurrentRoute($uri);

        // HACK: We will need that route for back links

        Registry::set('back_link',$uri);
        $this->params($uri,$url);
        Dispatcher::dispatch($this->route);
        return;
      }
    }
    include PUBLIK . DS . '404.html';
    header('Content-Type: text/html; charset=utf8');
    header('HTTP/1.1 404 Not Found');
    exit();
  }

  public function params($uri,$url)
  {
    if (Request::method() == 'post'){

      foreach ($_POST as $value) {
          $this->route->params[key($_POST)] = $value;
          next($_POST);
      }
      foreach ($_FILES as $value) {
          $this->route->params[key($_FILES)] = $value;
          next($_FILES);
      }
      reset($_FILES);
      reset($_POST);
    }

    $normalized = $this->normalize($uri,false);

    if (preg_match_all("#$normalized#",$url,$params)){
      preg_match_all("#$normalized#",$uri,$keys);

      for ($i=1; $i <= sizeof($params) - 1; $i++){
        $this->route->params[ltrim($keys[$i][0],':')] = $params[$i][0];
      }
    }
    
    $string = Url::getUrl(true);
    if (strpos($string,'?')) {
        $query = substr($string,strpos($string,'?') + 1);
        $query = explode('&',$query);
        $query = array_map(function ($item){
            return explode('=',$item);
        },$query);

        foreach ($query as $value) {
            $this->route->params[$value[0]] = $value[1];
        }
    }
  }

  /**
   * set route specific to current request
   * @param string uri pattern key
   * @return void;
   */
  public function setCurrentRoute($uri)
  {
    if (Request::method() === 'get')
    {
      $this->route = self::$getRoutes[$uri];
      return;
    }
    $this->route = self::$postRoutes[$uri];
  }

  /**
   * get routes that match the current request
   * @return array routes;
   */
  public function getCurrentRoutes()
  {
    if (Request::method() === 'get')
    {
      return self::$getRoutes;
    }
    return self::$postRoutes;
  }

  /**
   * normalize a uri pattern to be regex compatible
   * @param string uri pattern
   * @return string regex compatible string;
   */
  public function normalize($uri,$for_pattern = true)
  {
    $uri_array = explode('/',$uri);
    $return_array = [];

    foreach ($uri_array as $key => $value)
    {
      if($for_pattern)
      {
        $elem = preg_replace('/:\w+/','\\w+',$value);
      }else{
        $elem = preg_replace('/:\w+/','(.*)',$value);
      }

      //if it is the root route
      if ($value === 'root')
      {
        return '\/';
      }

      if ($key < sizeof($uri_array)-1)
      {
        $elem = $elem . '\\';
      }
      $return_array[] = $elem;
    }
    return implode('/',$return_array);
  }
}
