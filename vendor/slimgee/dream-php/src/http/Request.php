<?php
namespace Dream\Http;

use Psr\Http\Message\{
    MessageInterface,
    UriInterface,
    RequestInterface,
    StreamInterface
};
use Dream\Http\{
    Message,
    Uri,
    Stream,
    TextStream
};

/**
 *
 */
class Request extends Message implements RequestInterface
{
    protected $method;

    protected $uriObj;

    public function __construct($uri = null, $method = null, StreamInterface $body = null, $headers = null, $version = null)
    {
        $this->uri = $uri;
        $this->method = $this->checkMethod($method);
        $this->body = $body;
        $headers = is_array($headers) ? $headers : [];
        $this->httpHeaders = array_map(function ($value){
            return [$value];
        },$headers);
        $this->version = $this->onlyVersion($version);
    }

    /**
     * Checks if method is one of known http methods
     * @param string $method http method supplied
     * @return string\null method
     * @throws InvalidArgumentException on unknown method
     */
    protected function checkMethod($method)
    {
        if (!is_null($method)) {
            if (!in_array(strtolower($method),Constants::HTTP_METHODS)) {
                throw new InvalidArgumentException(Constants::ERROR_HTTP_METHOD, 1);
            }
        }
        return $method;
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget()
    {
        return $this->uri ?? Constants::DEFAULT_REQUEST_TARGET;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     * @param mixed $requestTarget
     * @return static
     */
    public function withRequestTarget($requestTarget)
    {
        $copy = clone $this;
        $copy->uri = $requestTarget;
        $copy->getUri();
        return $copy;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     * @return static
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method)
    {
        $copy = clone $this;
        $copy->method = $this->checkMethod($method);
        return $copy;
    }

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri()
    {
        if (!$this->uriObj) {
            $this->uriObj = new Uri($this->uri);
        }
        return $this->uriObj;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $copy = clone $this;
        if ($preserveHost) {
            $found = $this->findHeader(Constants::HEADER_HOST);
            if (!$found && $uri->getHost()) {
                $copy->httpHeaders[Constants::HEADER_HOST] = $uri->getHost();
            }
        } elseif ($uri->getHost()) {
            $copy->httpHeaders[Constants::HEADER_HOST] = $uri->getHost();
        }
        $copy->uriObj = $uri;
        $copy->uri = $copy->uriObj->__toString();
        return $copy;
    }
}
