<?php

namespace JFlahaut\GuzzleOauth2Client\GrantType;


use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use InvalidArgumentException;
use JFlahaut\GuzzleOauth2Client\AccessToken;
use JFlahaut\GuzzleOauth2Client\Exceptions\AccessTokenResponseException;

abstract class GrantType implements GrantTypeInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $grantType = '';

    /**
     * GrantType constructor.
     * @param ClientInterface $client
     * @param array $config
     */
    public function __construct(ClientInterface $client, array $config)
    {
        $this->client = $client;
        $this->config = array_merge($this->getDefaults(), $config);

        foreach ($this->getRequired() as $key) {
            if(!isset($this->config[$key]) || empty($this->config[$key])) {
                throw new InvalidArgumentException(sprintf('The config is missing the following key: "%s"', $key));
            }
        }
    }

    /**
     * @return array
     */
    abstract protected function getRequired(): array;

    /**
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * @return AccessToken|null
     * @throws AccessTokenResponseException
     * @throws GuzzleException
     * @throws Exception
     */
    public function getToken(): ?AccessToken
    {
        $response = $this->client->post($this->config['token_url'], $this->getRequestOptions());
        $data = Utils::jsonDecode($response->getBody(), true);

        if(isset($data['access_token'])) {
            return new AccessToken($data['access_token'], $data['token_type'], $data);
        } else {
            throw new AccessTokenResponseException($response);
        }
    }

    /**
     * Get default configuration items.
     *
     * @return array
     */
    protected function getDefaults(): array
    {
        return [
            'token_url' => '/oauth/token',
            'auth_location' => RequestOptions::FORM_PARAMS,
            'scope' => '',
        ];
    }

    /**
     * Get Guzzle request options
     *
     * @return array
     */
    protected function getRequestOptions(): array
    {
        $body = $this->config;
        $body['grant_type'] = $this->getGrantType();
        unset($body['token_url'], $body['auth_location']);

        $requestOptions = [];
        $requestOptions[$this->config['auth_location']] = $body;

        return $requestOptions;
    }

}