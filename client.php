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
use Dream\Http\Client;
use Dream\Http\Factory\ServerRequest as ServerFactory;


$body = new TextStream('');
$request = new Request(
    new Uri('http://localhost/'),
    Constants::METHOD_GET,
    $body,
    [
        'Accept' => 'application/html',
        'User-Agent' => 'Dream-Client'
    ]
);

$client = new Client();

$response = $client->sendRequest($request);

echo $response->getBody()->getContents();
