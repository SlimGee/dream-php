<?php
namespace Dream\Http\Sessions;

/**
 *
 */
class Session
{

  protected static $instance;

  private function __construct()
  {
      session_start();
      session_regenerate_id();
  }

  public static function set($key,$value)
  {
    $_SESSION[$key] = $value;
  }

  public static function get($key)
  {
    return $_SESSION[$key];
  }

  public static function check($key)
  {
      if (array_key_exists($key,$_SESSION)) {
          return isset($_SESSION[$key]);
      }
      return false;
  }

  public static function init()
  {
     if(!isset(self::$instance)){
       self::$instance = new static;
     }
     return self::$instance;
 }

 public static function erase($key)
 {
     unset($_SESSION[$key]);
 }

 public static function has($key)
 {
     return isset($_SESSION[$key]);
 }
}
