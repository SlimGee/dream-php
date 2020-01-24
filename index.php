<?php
require_once 'vendor/autoload.php';

use Dream\Http\Uri;
use Dream\Http\UploadedFile;
use Dream\Http\Stream;
use Dream\Http\TextStream;
use Dream\Http\Constants;
use Dream\Http\Message;

$uri = new Uri();
$new = $uri->withScheme('http')
    ->withHost('welodge')
    ->withPath('listings')
    ->withQuery('q=harare');

echo $new;
