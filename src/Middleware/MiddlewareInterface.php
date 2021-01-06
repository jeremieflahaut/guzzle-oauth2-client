<?php


namespace JFlahaut\GuzzleOauth2Client\Middleware;

use Closure;

interface MiddlewareInterface
{
    /**
     * @return Closure
     */
    public function onBefore(): Closure;

    /**
     * @param int $limit
     * @return mixed
     */
    public function onError(int $limit = 3): Closure;

}