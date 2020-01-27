<?php
namespace Dream;

use Dream\Registry;
use Dream\Database\Database;
use Dream\Patterns\Observer\Event;
use Dream\Views\View;
use Dream\Errors\Error;
use Dream\Http\Flush;
use Dream\Http\Routes\Router;
use Dream\Http\Sessions\{Session,Cookie};
use Dream\Container\Container;

class Application extends Container
{
    /**
     * Dream version
     */
    private $version = "0.0.1";

    private $basePath;
    /**
     * @var application router
     */
    public $router;

    /**
     * class constructor
     */
    public function __construct()
    {
        $this->set_reporting();
        $this->unregister_globals();
        $this->basePath = ROOT;
    }

    /**
     * set error reporting
     * @return void
     */
    private function set_reporting()
    {
         if(DEBUG){
             error_reporting(E_ALL);
             ini_set('display_errors',1);
             return;
         }
         error_reporting(0);
         ini_set('display_errors',0);
         ini_set('log_errors',1);
         ini_set('error_log',ROOT.DS.'tmp'.DS.'logs'.DS.'errors.log');
     }

     /**
      * unregister globas
      * @return void;
      */
     private function unregister_globals()
     {
          if(ini_get('register_globals')){
              $globalsArray = ['_POST','_GET','_SERVER','_FILES','_REQUEST','_COOKIE','_ENV'];
              foreach($globalsArray as $g){
                  foreach($GLOBALS[$g] as $k=>$v){
                      if($GLOBALS[$k]===$v){
                          unset($GLOBALS[$K]);
                      }
                  }
              }
          }
      }

      /**
       * start running the application
       * bootstraping the application
       * @return void;
       */
      public function start()
      {
          //initialize session and register
          Registry::set('session',Session::init());

          //initialize configuration
          $config = new Config();

          //Register
          Registry::set('config', $config);

          //initialize database
          //$db = new Database;

          //connect database
          // $connection = $db->new_connection(
          // $config->db->host,
          //$config->db->user,
          //$config->db->password,
          //$config->db->database
          //);

          //set current connection
          // $db->set_active_connection($connection);

          //Register
          //Registry::set('db',$db);

          //flush
          Registry::set('flush',new Flush());

          Registry::set('token',bin2hex(random_bytes(64)));
      }

      public function assemble($class,$parts = [])
      {
          return (new \ReflectionClass($class))->newInstanceArgs($parts);
      }

      public function basePath()
      {
          return $this->basePath;
      }
}
