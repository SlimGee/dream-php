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

/*---------------------------------------------------------------------------
|   Lets enjoy some dreams
|----------------------------------------------------------------------------
| This is our request handler
*/
$handler = require_once 'bootstrap/app.php';


/*---------------------------------------------------------------------------
|   Lets Compose A Dream Application
|----------------------------------------------------------------------------
| This is the response
*/
$response = $handler->handle(
    Dream\Http\Factory\Kernel::fromGlobals()
);

/*---------------------------------------------------------------------------
|   Mission Accomplished
|----------------------------------------------------------------------------
| You must have woken by now lets send the response
*/
//$response->send();

Dream\Mail\Mailer::mail();
