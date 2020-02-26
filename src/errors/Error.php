<?php
namespace Dream\Errors;
/**
 *
 */
class Error
{

  function __construct()
  {
    // code...
  }

  public static function __invoke($errno, $errstr,$error_file,$error_line){
    echo "<b>Error:</b> [$errno] $errstr - $error_file:$error_line";
    echo "<br />";
    echo "Terminating PHP Script";
    die();
  }
}
