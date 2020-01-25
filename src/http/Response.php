<?php
namespace Dream\Http;

use Psr\Http\Message\ResponseInterface;

/**
 *
 */
class Response extends Message implements ResponseInterface
{
    protected $statusCode;

    /**
     * initializes a response object with all the properties set
     */
    public function __construct($statusCode = null,StreamInterface $body = null,$headers = null,$version = null)
    {
        $this->status['code'] = $statusCode ?? Constants::DEFAULT_STATUS_CODE;
        $this->status['reason'] = Constants::STATUS_CODES[$statusCode] ?? '';
        $this->body = $body;
        $headers = is_array($headers) ? $headers : [];
        $this->httpHeaders = array_map(function ($value){
            return [$value];
        },$headers);
        $this->version = $this->onlyVersion($version);
        if ($statusCode) $this->setStatusCode();
    }
    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->status['code'];
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $copy = clone $this;
        if (!array_key_exists($code,Constants::STATUS_CODES)) {
            throw new InvalidArgumentException(Constants::ERROR_BAD . 'status code');
        }
        $copy->status['code'] = $code;
        $copy->status['reason'] = ($reasonPhrase) ? Constants::STATUS_CODES[$code] : null;
        $copy->setStatusCode();
        return $copy;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {
        return $this->status['reason'] ?? Constants::STATUS_CODES[$this->status['code']] ?? '';
    }

    /**
     * Sets the status regardless of the headers sent
     */
    public function setStatusCode()
    {
        http_response_code($this->getStatusCode());
    }

    /**
     * Actually send the response
     */
    public function send()
    {
        foreach ($message->getHeaders() as $name => $values) {
           foreach ($values as $value) {
               header(sprintf('%s: %s', $name, $value), false);
           }
        }
        echo $this->getBody()->getContents();
    }
}
