<?php
require_once 'vendor/autoload.php';

use Dream\Http\Uri;
use Dream\Http\UploadedFile;
use Dream\Http\Stream;
use Dream\Http\TextStream;
use Dream\Http\Constants;
use Dream\Http\Message;
use Dream\Http\ServerRequest;
use Dream\Http\Request;

$request = new ServerRequest();
$request->initialize();
$request->getHeaders();
$request->getProtocolVersion();
$uri = new Uri();
$uri = $uri->withHost($request->getHeaderLine('host')[0])
            ->withPort((int)$request->getServerParams()['SERVER_PORT'])
            ->withPath($request->getServerParams()['PATH_INFO'] ?? '')
            ->withQuery($request->getServerParams()['QUERY_STRING'] ?? '');
$request = $request->withUri($uri,true);


echo '<pre>';
var_dump($request);
echo '</pre>';
