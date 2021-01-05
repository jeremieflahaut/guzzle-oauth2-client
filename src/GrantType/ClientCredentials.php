<?php

namespace JFlahaut\GuzzleOauth2Client\GrantType;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use JFlahaut\GuzzleOauth2Client\AccessToken;
use JFlahaut\GuzzleOauth2Client\Exceptions\AccessTokenResponseException;
use Exception;

class ClientCredentials extends GrandType
{

    protected $client;

    protected $config;

    CONST GRANT_TYPE = 'client_credentials';

    public function __construct(ClientInterface $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getGrandType(): string
    {
        return self::GRANT_TYPE;
    }

    /**
     * @return AccessToken|null
     * @throws AccessTokenResponseException
     * @throws GuzzleException
     * @throws Exception
     */
    public function getToken(): ?AccessToken
    {
            $body = $this->config;
            $body['grant_type'] = $this->getGrandType();
            unset($body['token_url'], $body['auth_location']);
            $requestOptions = [];

            if(!isset($this->config['auth_location'])) {
                $requestOptions[RequestOptions::FORM_PARAMS] = $body;
            } else {
                $requestOptions[$this->config['auth_location']] = $body;
            }

            $response = $this->client->post($this->config['token_url'], $requestOptions);
            $data = Utils::jsonDecode($response->getBody(), true);

            if(isset($data['access_token'])) {
                return new AccessToken($data['access_token'], $data['token_type'], $data);
            } else {
                throw new AccessTokenResponseException($response);
            }
    }


}
