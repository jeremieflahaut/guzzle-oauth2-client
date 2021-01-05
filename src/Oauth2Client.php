<?php

namespace JFlahaut\GuzzleOauth2Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use JFlahaut\GuzzleOauth2Client\Middleware\MiddlewareInterface;

class Oauth2Client
{

    protected $client;

    public function __construct(MiddlewareInterface $middleware, array $config)
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push($middleware->onBefore());
        $handlerStack->push($middleware->onError());

        $config = array_merge([
            'handler' => $handlerStack,
            'auth' => 'oauth2'
        ], $config);

        $this->client = new HttpClient($config);
    }

    public static function create(MiddlewareInterface $middleware, array $config): Oauth2Client
    {
        return new static($middleware, $config);
    }

    public function postUser($data)
    {
        $response = $this->client->request('POST', '/api/users', [
            'form_params' => $data
        ]);

        dd($response);
    }

}