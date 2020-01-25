<?php
namespace Dream\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
/**
 *
 */
class Client implements ClientInterface
{
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $headers = [];

        foreach ($request->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $headers[] = sprintf('%s: %s', $name, $value);
            }
        }

        $options = [
             'http' => [
                 'method' => strtoupper($request->getMethod()),
                 'header' => $headers,
                 'content' => $request->getBody()->getContents(),
                 'ignore_errors' => 1
             ]
         ];

         $context = stream_context_create($options);
         $result = file_get_contents($request->getUri()->getUriString(),false,$context);
         if ($result === false) {
             $error = error_get_last();
             throw new \Exception("Error Processing Request: " . $error, 1);
         }
         $code = $this->getHttpCode($http_response_header);
         return (new Response())->withBody(new TextStream($result))->withStatus($code);
    }

    protected function getHttpCode($headers)
    {
        if (is_array($headers)) {
            $parts = explode(' ',$headers[0]);
            if(count($parts) > 1)
                return $parts[1];
        }
        return false;
    }
}
