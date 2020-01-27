<?php
namespace Dream\Http\Routes;

/**
 *
 */
class Url
{
  public static function getUrl($withQueryString = false)
  {
      $url = trim($_SERVER['REQUEST_URI']);
      if ($withQueryString == true) {
          return $url;
      }
      $mark = strpos($url,'?');
      if ($mark !== false) {
          return substr($url,0,$mark);
      }
      return $url;
  }
}
