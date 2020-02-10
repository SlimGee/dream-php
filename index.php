<?php
/*---------------------------------------------------------------------------
|   The starting point
|----------------------------------------------------------------------------
| A base path
*/
defined('ROOT') || define('ROOT', __DIR__);

/*---------------------------------------------------------------------------
|   Lets register The autoloader
|----------------------------------------------------------------------------
| It will be a nightmare if you don't
*/
require_once 'vendor/autoload.php';

$config = require_once 'config/config.php';

/*---------------------------------------------------------------------------
|   Lets enjoy some dreams
|----------------------------------------------------------------------------
| This is our request handler
*/
$app = require_once 'bootstrap/app.php';

$app->boot($config);

/*---------------------------------------------------------------------------
|   Lets create a router
|----------------------------------------------------------------------------
| This is will router our request
*/

$app = $app->configure([
    'substitute' => [
        Psr\Http\Server\RequestHandlerInterface::class => Dream\Route\Dispatcher::class
    ]
]);


/*---------------------------------------------------------------------------
|   Lets Compose A Dream Application
|----------------------------------------------------------------------------
| This is the response
*/
$handler = $app->get(
    Dream\Http\RequestHandler::class
);

/*---------------------------------------------------------------------------
|   Routing Middleware
|----------------------------------------------------------------------------
| This is our request handler
*/

$handler->add(
    $app->get(Dream\Http\Middleware\BackLink::class)
);
$handler->add(
    $app->get(Dream\Http\Middleware\Routing::class)
);


$response = $handler->handle(
    Dream\Http\Factory\Kernel::fromGlobals()
);

/*---------------------------------------------------------------------------
|   Mission Accomplished
|----------------------------------------------------------------------------
| You must have woken by now lets send the response
*/
$response->send();
