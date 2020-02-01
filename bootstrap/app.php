<?php
/*---------------------------------------------------------------------------
|   Lets simplify it
|----------------------------------------------------------------------------
| I'm sure you hate to write 'DIRECTORY_SEPARATOR'
*/
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

/*---------------------------------------------------------------------------
|   Even when dreaming you need to know where you're going don't you
|----------------------------------------------------------------------------
| Lets get some routes
*/
require_once 'config/routes.php';

/*---------------------------------------------------------------------------
|   We have something common here
|----------------------------------------------------------------------------
| Some common useful functions
*/
require_once 'src/Common.php';

/*---------------------------------------------------------------------------
|   Some things will never change
|----------------------------------------------------------------------------
| Lets grab some constants
*/
require_once 'config/constants.php';


/*---------------------------------------------------------------------------
|   Trust us you need these
|----------------------------------------------------------------------------
| some application specific helper functions
*/
require_once 'app/helpers/functions.php';

/*---------------------------------------------------------------------------
|   What you always dreamed of
|----------------------------------------------------------------------------
| Our application
*/
$app = new Dream\Kernel\App(__DIR__);


/*---------------------------------------------------------------------------
|   Looks like our work is done
|----------------------------------------------------------------------------
| Lets give them a handler
*/
return $app;
