<?php
namespace Dream\Http;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

/**
 * Uri class
 */
class Uri implements UriInterface
{
    protected $uriString;

    protected $uriParts = [];

    protected $queryParams;

    /**
     * construts the object from uri string
     * @param string uri
     * @throws InvalidArgumentException when invalid uri passed
     * @return void
     */
    public function __construct(string $uriString = '')
    {
        if (empty($uriString)) {
            return;
        }
        $this->uriParts = parse_url($uriString);
        if (!$this->uriParts) {
            throw new \InvalidArgumentException(Constants::ERROR_INVALID_URI);
        }
        $this->uriString = $uriString;
    }

    /**
     * Retrieve the scheme component of the URI.
     * @return string The URI scheme;
     */
    public function getScheme()
    {
        return strtolower($this->uriParts['scheme']) ?? '';
    }

    /**
     * Retrieves the authority component of the URI
     *  @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority()
    {
        $val = '';
        if (!empty($this->getUserInfo())){
            $val .= $this->getUserInfo() . '@';
            $val .= $this->uriParts['host'] ?? '';
        }
        if (!empty($this->uriParts['port']) && !array_key_exists($this->getScheme(),Constants::STANDARD_PORTS))
            $val .= ':' . $this->uriParts['port'];
        return $val;
    }

    /**
     * Retrieves user information component from the URI
     *  @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo()
    {
        if (empty($this->uriParts['user'])) {
            return '';
        }
        $val = $this->uriParts['user'];
        if (!empty($this->uriParts['pass']))
            $val .= ':' . $this->uriParts['pass'];
        return $val;
    }

    /**
     * Retrieves the host component of the URI
     * @return string The URI host;
     */
    public function getHost()
    {
        if (empty($this->uriParts['host'])) {
            return '';
        }
        return strtolower($this->uriParts['host']);
    }

    /**
     * Retrives the port component of the URI
     * @return null|int The URI port;
     */
    public function getPort()
    {
        if (empty($this->uriParts['port'])) {
            return NULL;
        } else {
            if ($this->getScheme()) {
                if ($this->uriParts['port'] == Constants::STANDARD_PORTS[$this->getScheme()]) {
                    return NULL;
                }
            }
        }
        return (int) $this->uriParts['port'];
    }

    /**
     * Retrieves the path component of the URI
     * @return string The URI path.
     */
    public function getPath()
    {
        if (empty($this->uriParts['path'])) {
            return '';
        }
        return implode('/', array_map("rawurlencode",explode('/', $this->uriParts['path'])));
    }

    /**
     * Retrieves the query component of the URI
     * @return string The URI query string.
     */
    public function getQuery()
    {
        if (!$this->getQueryParams()) {
            return '';
        }
        $output = '';
        foreach ($this->getQueryParams() as $key => $value) {
            $output .= rawurlencode($key) . '='. rawurlencode($value) . '&';
        }
        return substr($output, 0, -1);
    }

    /**
     * Retrieves the query params of the URI
     * @return array The URI params
     */
    public function getQueryParams($reset = FALSE)
    {
        if ($this->queryParams && !$reset) {
            return $this->queryParams;
        }
        $this->queryParams = [];
        if (!empty($this->uriParts['query'])) {
            foreach (explode('&', $this->uriParts['query']) as $keyPair) {
                list($param,$value) = explode('=',$keyPair);
                $this->queryParams[$param] = $value;
            }
        }
        return $this->queryParams;
    }

    /**
     * Retrives the fragment component of the URI
     * @return string The URI fragment;
     */
    public function getFragment()
    {
        if (empty($this->urlParts['fragment'])) {
        return '';
        }
        return rawurlencode($this->urlParts['fragment']);
    }

    /**
     * Returns an instance with specified scheme
     * @param string $scheme The scheme to use with the new instance.
     * @return static A new instance with specified scheme
     * @throws InvalidArgumentException for invalid or unsupported schemes
     */
    public function withScheme($scheme)
    {
        if (empty($scheme) && $this->getScheme()) {
            unset($this->uriParts['scheme']);
        } else {
            if (isset(Constants::STANDARD_PORTS[strtolower($scheme)])) {
                $this->uriParts['scheme'] = $scheme;
            } else {
                throw new InvalidArgumentException(Constants::ERROR_BAD . __METHOD__);
            }
        }
        return $this;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null)
    {
        if (empty($user) && $this->getUserInfo()) {
            unset($this->uriParts['user']);
        } else {
            $this->uriParts['user'] = $user;
            if ($password) {

            $this->uriParts['pass'] = $password;
            }
        }
        return $this;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host)
    {
        if (empty($host)) {
            unset($this->uriParts['host']);
            return $this;
        }
        $this->uriParts['host'] = $host;
        return $this;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return static A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port)
    {
        if (is_null($port)) {
            unset($this->uriParts['port']);
            return $this;
        }
        // FIXME: throw exception for ports outside range of established TCP and UDP port ranges
        $this->uriParts['port'] = $port;
        return $this;
    }

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative then
     * it must begin with a slash ("/"). Paths not starting with a slash ("/")
     * are assumed to be relative to some base path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return static A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path)
    {
        // FIXME: throw exception for invalid path
        // QUESTION: how to determine if path is invalid
        $this->uriParts['path'] = $path;
        return $this;
    }

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return static A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query)
    {
        if (empty($query)) {
            unset($this->uriParts['query']);
            return $this;
        }
        // FIXME: throw exceptions for invalid query strings
        // QUESTION: how to determine invalid query strings
        $this->uriParts['query'] = $query;
        //reset the query params array
        $this->getQueryParams(true);
        return $this;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return static A new instance with the specified fragment.
     */
    public function withFragment($fragment)
    {
        if (empty($fragment)) {
            unset($this->uriParts['fragment']);
            return $this;
        }
        // QUESTION: how to determine if fragment is encoded
        $this->uriParts['fragment'] = $fragment;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString()
    {
        $uri = ($this->getScheme()) ? $this->getScheme() . ':' : '';
        if ($this->getAuthority()) {
            $uri .= '//' . $this->getAuthority();
        } else {
            $uri .= ($this->getHost()) ? '//' . $this->getHost() : '';
            $uri .= ($this->getPort()) ? ':' . $this->getPort() : '';
        }

        $path = $this->getPath();
        if ($path) {
            if ($path[0] !== '/') {
                $uri .= '/' . $path;
            } else {
                $uri .= $path;
            }
        }
        $uri .= ($this->getQuery()) ? '?' . $this->getQuery() : '';
        $uri .= ($this->getFragment()) ? '#' . $this->getFragment() : '';
        return $uri;
    }

    /**
     * Returns current instance as a string
     * @return string The URI;
     */
    public function getUriString()
    {
        return $this->__toString();
    }
}
