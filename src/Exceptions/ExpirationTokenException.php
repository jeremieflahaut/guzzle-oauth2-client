<?php

namespace JFlahaut\GuzzleOauth2Client\Exceptions;

use Exception;
use Throwable;


class ExpirationTokenException extends Exception
{
    /**
     * ExpirationTokenException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = 'Invalid token expiration: '. $message;

        parent::__construct($message, $code, $previous);
    }

}