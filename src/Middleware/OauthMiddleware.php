<?php

namespace JFlahaut\GuzzleOauth2Client\Middleware;


use GuzzleHttp\ClientInterface;
use JFlahaut\GuzzleOauth2Client\AccessToken;
use JFlahaut\GuzzleOauth2Client\GrantType\GrandTypeInterface;
use Psr\Http\Message\RequestInterface;
use Closure;
use Psr\Http\Message\ResponseInterface;

class OauthMiddleware extends Middleware
{
    protected $client;

    protected $grantType;

    protected $accessToken;

    public function __construct(ClientInterface $client, GrandTypeInterface $grantType)
    {
        $this->client = $client;

        $this->grantType = $grantType;

    }

    public function onBefore(): Closure
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if(isset($options['auth']) && $options['auth'] === 'oauth2') {
                    $token = $this->getAccessToken();
                    if ($token !== null) {
                        return $handler($request->withAddedHeader('Authorization', 'Bearer '.$token->getToken()), $options);
                    }
                }

                return $handler($request, $options);
            };
        };
    }

    public function onError(int $limit = 3)
    {

        $calls = 0;

        return function (callable $handler) use (&$calls, $limit) {
            return function (RequestInterface $request, array $options) use($handler, &$calls, $limit) {
                $promise = $handler($request, $options);

                return $promise->then(
                    function (ResponseInterface $response) use ($request, $options, &$calls, $limit) {

                        if($response->getStatusCode() == 401) {

                            ++$calls;

                            if ($calls > $limit) {
                                return $response;
                            }

                            if ($token = $this->getAccessToken()) {
                                $response = $this->client->send($request->withHeader('Authorization', 'Bearer '.$token->getToken()), $options);
                            }
                        }

                        return $response;
                    }
                );
            };
        };
    }

    protected function getAccessToken(): AccessToken
    {
        if(!($this->accessToken instanceof AccessToken) || $this->accessToken->isExpired()) {
            $this->acquireAccessToken();
        }

        return $this->accessToken;
    }

    protected function acquireAccessToken()
    {
        if (!$this->accessToken || $this->accessToken->isExpired()) {
            $this->accessToken = $this->grantType->getToken();
        }

        return $this->accessToken;
    }

}