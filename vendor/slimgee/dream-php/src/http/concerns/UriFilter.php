<?php
namespace Dream\Http\Concern;

use InvalidArgumentException;
use Dream\Http\Constants;

/**
 *
 */
trait UriFilter
{
    /**
     * Checks if a port is within range of defined TCP and UDP ports
     * @param int $port The port to validate
     * @throws InvalidArgumentException on invalid ports
     * @return int The valid port
     */
    private function filterPort($port)
    {
        if ($port === null) {
            return null;
        }
        $port = (int)$port;
        if (1 > $port || 0xffff < $port) {
            throw new InvalidArgumentException(Constants::ERROR_BAD . sprintf('Invalid port %d. Must be between 1 and 65535',$port));
        }
        return $port;
    }

    /**
     * Validate path
     * @param string $path The path to validate
     * @throws InvalidArgumentException on invalid paths
     * @return string The valid path
     */
    private function filterPath($path)
    {
        if (!is_string($path)) {
            throw new InvalidArgumentException(Constants::ERROR_BAD . 'Path must be string');
        }
        return $path;
    }

    /**
     * Validates a query string or url fragment
     * @param $str The fragment or query string to validate
     * @throws InvalidArgumentException on invaid query string or fragment
     * @return string The valid query string or fragment
     */
    private function filterQueryAndFragment($str)
    {
        if (!is_string($str)) {
            throw new InvalidArgumentException(Constants::ERROR_BAD . 'Invalid query or fragment');
        }
        return $str;
    }
}
