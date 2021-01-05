<?php

namespace JFlahaut\GuzzleOauth2Client\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Exception;
use Throwable;


class AccessTokenResponseException extends Exception
{
    public function __construct(ResponseInterface $response, $code = 0, Throwable $previous = null)
    {
        $message = 'Invalid Access_token Response: ' . $response->getBody()->getContents();

        parent::__construct($message, $code, $previous);
    }

}