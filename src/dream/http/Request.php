<?php
namespace Dream\Http;

/**
 * Request class
 */
class Request
{

  /**
   * the http verb
   */
  public static function method()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }
}
