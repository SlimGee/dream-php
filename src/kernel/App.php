<?php

namespace Dream\Kernel;

use Dream\Container\Container;

/**
 *
 */
class App extends Container
{
    /**
     * Dream version
     */
    protected $version = "0.0.1";

    /**
     * Wheter appliation is started
     * @var bool
     */
    protected $isStarted = false;

    /**
     * Whether app is down for maintence
     * @var bool
     */
    protected $isDown = false;

    /**
     * Base application path
     * @var bool
     */
    protected $basePath;

    /**
     * The environment
     * @var string
     */
    protected $environment;

    /**
     * The application registry
     * @var Dream\Kernel\Registry
     */
    protected $registry;

    /**
     * Application configuration
     * @var Dream\Kernel\Config
     */
    protected $config;

    /**
     * The application's instance
     */
    protected static $instance;

    /**
     * Constructor
     */
    public function __construct($basePath)
    {
        if (!is_dir($basePath) || !is_readable($basePath)) {
            throw new \Exception("Base path should be a readable directory", 1);
        }
        if (isset(self::$instance)) {
            throw new \Exception("Application can only be booted once", 1);
        }
        self::$instance = $this;
        $this->basePath = $basePath;
        $this->unregisterGlobals();
        $this->environment = \Config\Environments\Environment::ENV;
        $this->setErrorReporting();
    }

    /**
     * Unregister globals
     */
    public function unregisterGlobals()
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
     * Error Reporting
     */
    public function setErrorReporting()
    {
        if($this->environment === "development"){
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
     * Returns the base path of the application
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Boots the applicatio
     * This is where the pats are glued together
     */
    public function boot(array $config)
    {
        // initialize the appliation's registry
        $this->registry = $this->get(
            \Dream\Kernel\Registry::class
        );

        //initialize session and register
        $this->registry->set('session',$this->get(
            \Dream\Session\Session::class
            )
        );

        //initialize session and register
        $this->registry->set('cookie',$this->get(
            \Dream\Session\Cookie::class
            )
        );

        //Register Application configuration
        $this->configure([
            \Dream\Kernel\Config::class => [
                'config' => $config
            ]
        ]);

        $this->registry->set('config',$this->get(
            \Dream\Kernel\Config::class, [$config]
            )
        );

        $this->config = $this->registry->get('config');

        //initialize database
        $db = $this->get(
            \Dream\Database\Database::class
        );

        //connect database
        $connection = $db->new_connection(
            $this->config->db->host,
            $this->config->db->user,
            $this->config->db->password,
            $this->config->db->database
        );

        //set current connection
        $db->set_active_connection($connection);

        //Register
        $this->registry->set('db',$db);

        //flush
        $this->registry->set('flush',$this->get(
            \Dream\Flush\FlushMessage::class
            )
        );

        $this->registry->set('token',bin2hex(openssl_random_pseudo_bytes(128)));
    }

    /**
     * Returns the application's registry store
     * @return Dream\Kernel\Registry The appliation registry
     */
    public function registry()
    {
        return $this->registry;
    }
    /**
     * get the appliation's instance
     * @return Dream\Kernel\App The appliation
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = (new static);
        }
        return self::$instance;
    }
}
