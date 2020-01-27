<?php
namespace Dream;
/**
 * Config
 */
class Config
{

  function __construct()
  {
    include 'config/config.php';
    $this->assign($this,$config);
  }

  public function assign($class,array $data)
  {
    foreach ($data as $key => $value)
    {
      if (is_array($value))
      {
        $class->$key = new \stdClass();
        foreach ($value as $nkey => $nvalue)
        {
          if (is_array($nvalue))
          {
            $this->assign($class->$key,$nvalue);
            continue;
          }
          $class->$key->$nkey = $nvalue;
        }
      continue;
      }
      $class->$key = $value;
    }
  }
}
