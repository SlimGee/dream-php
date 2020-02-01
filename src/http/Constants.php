<?php
namespace Dream\Http;

/**
 * Contains useful constants to be used within the framework
 */
class Constants
{
    const HEADER_HOST               =  'Host';
    const HEADER_CONTENT_TYPE       =  'Content-Type';
    const HEADER_CONTENT_LENGTH     =  'Content-Length';
    const METHOD_GET                =  'get';
    const METHOD_POST               =  'post';
    const METHOD_PUT                =  'put';
    const METHOD_DELETE             =  'delete';
    const HTTP_METHODS              =  ['get','put','post','delete'];
    const STANDARD_PORTS            =  [
        'ftp' => 21, 'ssh' => 22, 'http' => 80, 'https' => 443
    ];

    const CONTENT_TYPE_FORM_ENCODED = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE_MULTI_FORM   = 'multipart/form-data';
    const CONTENT_TYPE_JSON         = 'application/json';
    const CONTENT_TYPE_HAL_JSON     = 'application/hal+json';
    const DEFAULT_STATUS_CODE       = 200;
    const DEFAULT_BODY_STREAM       = 'php://temp';
    const DEFAULT_REQUEST_TARGET    = '/';
    const MODE_READ                 = 'r';
    const MODE_WRITE                = 'w';
    // NOTE: not all error constants are shown to conserve space
    const ERROR_BAD                 = 'ERROR: ';
    const ERROR_UNKNOWN             = 'ERROR: unknown';
    const ERROR_INVALID_URI         = 'ERROR: invalid uri string';
    const ERROR_BAD_DIR             = 'ERROR: Directory should be both readble and writable';
    const ERROR_BAD_FILE            = 'ERROR: Bad file';
    const ERROR_FILE_NOT            = 'ERROR: File not uploaded';
    const ERROR_MOVE_DONE           = 'ERROR: File already moved';
    const ERROR_MOVE_UNABLE         = 'ERROR: Unable to move file';
    const ERROR_BODY_UNREADABLE     = 'ERROR: Could not read the body of the message';
    const ERROR_HTTP_METHOD         = 'ERROR: Http method unknown';
    const STATUS_CODES              = [
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        401 => 'Unauthorized',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        418 => 'I_m A Teapot',
        500 => 'Internal Server Error',
    ];
}
