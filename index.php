<?php
require_once 'vendor/autoload.php';

use Dream\Http\Uri;

$uri = new Uri("https://root@localhost/chap_09_middleware_value_objects_uri.php?param=TES");
echo $uri;
