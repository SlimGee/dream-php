<?php
namespace Dream\Http;

use Psr\Http\Message\MessageInterface;

/**
 * Http Message implementaion
 */
class Message implements MessageInterface
{
    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }
}
