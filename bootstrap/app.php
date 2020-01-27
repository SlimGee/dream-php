<?php
/*---------------------------------------------------------------------------
|   Lets simplify it
|----------------------------------------------------------------------------
| I'm sure you hate to write 'DIRECTORY_SEPARATOR'
*/
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

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
|   Even when dreaming you need to know where you're going don't you
|----------------------------------------------------------------------------
| Lets get some routes
*/
require_once 'config/routes.php';

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
$app = new Dream\Application;

/*---------------------------------------------------------------------------
|   The Power button
|----------------------------------------------------------------------------
| Lets press it
*/
$app->start();

/*---------------------------------------------------------------------------
|   Lets create a handler
|----------------------------------------------------------------------------
| This is our request handler
*/
$handler = $app->assemble(
    Dream\Http\RequestHandler::class
);

/*---------------------------------------------------------------------------
|   Lets create a router
|----------------------------------------------------------------------------
| This is will router our request
*/
$router = $app->assemble(
    Dream\Route\Router::class,[new Dream\Route\Dispatcher]
);

/*---------------------------------------------------------------------------
|   Routing Middleware
|----------------------------------------------------------------------------
| This is our request handler
*/
$handler->add(
    $app->assemble(Dream\Http\Middleware\Routing::class,[$router])
);


/*---------------------------------------------------------------------------
|   Looks like our work is done
|----------------------------------------------------------------------------
| Lets give them a handler
*/
return $handler;
