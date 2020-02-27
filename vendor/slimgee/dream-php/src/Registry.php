<?php
namespace Dream{
  /**
   * Registry class
   */
  class Registry
  {
    protected static $instance = null;

    private static $objects = [];

    private function __construct()
    {
    }

    /**
     * singleton method
     * @return Registry instance;
     */
    public static function GetInstance(){
      if(static::$instance != null){
        return static::$instance;
      }
      static::$instance = new static;
      return static::$instance;
    }

    /**
     * add object to registry
     * @param string key to reference
     * @param object instance
     */
    public static function set($key,$instance){
      static::$objects[$key] = $instance;
    }

    /**
     * get object from the registry
     * @param string key
     * @return object instance;
     */
    public static function get($key){
      if(!isset(static::$objects[$key])){
        throw new \Exception("Object $key not stored in registry", 1);
      }
      return self::$objects[$key];
    }

    public static function erase($key)
    {
        unset(self::$objects[$key]);
    }
  }
}
