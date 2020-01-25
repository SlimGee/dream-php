<?php
namespace Dream\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 *
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    protected $serverParams;

    protected $cookies;

    protected $queryParams;

    protected $contentType;

    protected $parsedBody;

    protected $attributes;

    protected $uploadedFileInfo;

    protected $uploadedFiles;

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams()
    {
        if (!$this->serverParams) {
            $this->serverParams = $_SERVER;
        }
        return $this->serverParams;
    }

    /**
     * Retrives the content type of the current request
     * @return string The content type
     */
    public function getContentType()
    {
        if (!$this->contentType) {
            $this->contentType = $this->getServerParams()['CONTENT_TYPE'] ?? '';
            $this->contentType = strtolower($this->contentType);
        }
        return $this->contentType;
    }

    /**
     * Retrives the request method of the current request
     * @return string The Method
     */
     public function getRequestMethod()
     {
         $this->method = $this->getServerParams()['REQUEST_METHOD'] ?? '';
         $this->method = strtolower($this->method);
         return $this->method;
     }

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams()
    {
        if (!$this->cookies) {
            $this->cookies = $_COOKIE;
        }
        return $this->cookies;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $copy = clone $this;
        array_merge($copy->getCookieParams(),$cookies);
        return $copy;
    }

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from `getUri()->getQuery()`
     * or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams()
    {
        $queryStr = $this->getUri()->getQuery();
        $ret = [];
        if (empty($queryStr)) {
            return $ret;
        }
        array_walk(function ($str){
            list($name,$value) = explode('=',$str);
            $ret[$name] = $value;
        },explode('&',$queryStr));
        return $ret;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *     $_GET.
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $copy = clone $this;
        array_merge($copy->getQueryParams(),$query);
        return $copy;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     *
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return array An array tree of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    public function getUploadedFiles()
    {
        if (!$this->uploadedFiles) {
            $this->uploadedFileInfo = $_FILES;
            foreach ($this->uploadedFileInfo as $field => $value) {
                $this->uploadedFiles[$field] = new UploadedFile($field,$value);
            }
        }
        return $this->uploadedFiles;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array $uploadedFiles An array tree of UploadedFileInterface instances.
     * @return static
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        if (!count($uploadedFiles)) {
            throw new InvalidArgumentException(Constants::ERRO_NO_UPLOADED_FILES);
        }
        foreach ($uploadedFiles as $value) {
            if (!$value instanceof UploadedFileInterface) {
                throw new InvalidArgumentException(Constants::ERROR_INVALID_UPLOADED);
            }
        }
        $copy = clone $this;
        $copy->uploadedFiles = $uploadedFiles;
        return $copy;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @return null|array|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody()
    {
        if (($this->getContentType() == Constants::CONTENT_TYPE_MULTI_FORM ||
            $this->getContentType() == Constants::CONTENT_TYPE_FORM_ENCODED) &&
            $this->getRequestMethod() == Constants::METHOD_POST) {
            $this->parsedBody = $_POST;
        } elseif ($this->getContentType() == Constants::CONTENT_TYPE_JSON || $this->getContentType() == Constants::CONTENT_TYPE_HAL_JSON) {
            ini_set('allow_url_fopen',true);
            $this->parsedBody = json_decode(file_get_contents('php://input'));
        } elseif (!empty($_REQUEST)) {
            $this->parsedBody = $_REQUEST;
        } else {
            ini_set('allow_url_fopen',true);
            $this->parsedBody = file_get_contents('php://input');
        }
        return $this->parsedBody;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * These MAY be injected during instantiation.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     *
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or objects,
     * or a null value if nothing was available to parse.
     *
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param null|array|object $data The deserialized body data. This will
     *     typically be in an array or object.
     * @return static
     * @throws \InvalidArgumentException if an unsupported argument type is
     *     provided.
     */
    public function withParsedBody($data)
    {
        $copy = clone $this;
        if (!is_array($body) || !is_object($data) || !is_null($data)) {
            throw new InvalidArgumentException(Constants::ERROR_BAD_PARSED_DATA);
        }
        $copy->parsedBody = $data;
        return $copy;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attribute[$name] ?? $default;
    }

    /**
     * Checks if an attribute is present int the request
     * @param $name Name of the attribute to check
     * @return bool whether it is present
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name,$this->attributes);
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $copy = clone $this;
        $copy->attributes[$name] = $value;
        return $copy;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @return static
     */
    public function withoutAttribute($name)
    {
        $copy = clone $this;
        unset($copy->attributes[$name]);
        return $copy;
    }

    public function initialize()
    {
        $this->getServerParams();
        $this->getCookieParams();
        $this->getQueryParams();
        $this->getUploadedFiles();
        $this->getRequestMethod();
        $this->getContentType();
        $this->getParsedBody();
        return $this;
    }
}
