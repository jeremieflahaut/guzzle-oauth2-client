<?php

namespace JFlahaut\GuzzleOauth2Client\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Exception;
use Throwable;


class AccessTokenResponseException extends Exception
{
    /**
     * AccessTokenResponseException constructor.
     * @param ResponseInterface $response
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(ResponseInterface $response, $code = 0, Throwable $previous = null)
    {
        $message = 'Invalid Access_token Response: ' . $response->getBody()->getContents();

        parent::__construct($message, $code, $previous);
    }

}