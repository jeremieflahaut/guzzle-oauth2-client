<?php


namespace JFlahaut\GuzzleOauth2Client\Middleware;

use Closure;

interface MiddlewareInterface
{
    public function onBefore(): Closure;

    public function onError(int $limit = 3);

}