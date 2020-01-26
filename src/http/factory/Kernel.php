<?php

namespace Dream\Http\Factory;

use Dream\Http\Uri;
use Dream\Http\UploadedFile;
use Dream\Http\Stream;
use Dream\Http\TextStream;
use Dream\Http\Constants;
use Dream\Http\Message;
use Dream\Http\ServerRequest;
use Dream\Http\Request;
use Dream\Http\Response;
use Dream\Http\Factory\ServerRequest as ServerFactory;

/**
 *
 */
class Kernel
{
    public static function fromGlobals()
    {
        $uri = new Uri(self::figureUriString());
        $method = $_SERVER['REQUEST_METHOD'];
        return ServerFactory::createServerRequest($method, $uri, $_SERVER);
    }

    public static function figureUriString()
    {
        $uri = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $uri .= $_SERVER['SERVER_NAME'];
        $uri .= $_SERVER['REQUEST_URI'];
        return $uri;
    }
}
