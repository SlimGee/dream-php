#!/usr/bin/env php
<?php

define('ROOT', __DIR__);

require_once 'vendor/autoload.php';


$config = require_once 'config/config.php';

/*---------------------------------------------------------------------------
|   Lets enjoy some dreams
|----------------------------------------------------------------------------
| This is our request handler
*/
$app = require_once 'bootstrap/app.php';

$app->boot($config);

use Dream\Database\Migration\Column;
use Dream\Database\Migration\Console\Migrate;
use Dream\Database\Migration\Console\Migration;
use Dream\Database\ActiveRecord\Console\Model;
use Dream\Http\Controllers\Console\Controller;
use Symfony\Component\Console\Application;


$app = new Application('dream php', '0.0.1');

$app->add(new Migrate());
$app->add(new Migration());
$app->add(new Model());
$app->add(new Controller());

$app->run();
