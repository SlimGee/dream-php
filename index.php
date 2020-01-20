<?php
require_once 'vendor/autoload.php';

use Dream\Http\Uri;
use Dream\Http\UploadedFile;
use Dream\Http\Stream;
use Dream\Http\TextStream;
use Dream\Http\Constants;
use Dream\Http\Message;

$message = new Message();
echo $message->getProtocolVersion();
echo "<pre>";
$message->getBody()->seek(1);
var_dump($message->getBody()->tell());
