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

abstract class GrandType implements GrandTypeInterface
{

    protected $client;

    protected $config;

    protected $grantType = '';

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

    abstract protected function getRequired();

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
     * @return string
     */
    public function getGrandType(): string
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
        $body = $this->config;
        $body['grant_type'] = $this->getGrandType();
        unset($body['token_url'], $body['auth_location']);
        $requestOptions = [];

        $requestOptions[$this->config['auth_location']] = $body;

        $response = $this->client->post($this->config['token_url'], $requestOptions);
        $data = Utils::jsonDecode($response->getBody(), true);

        if(isset($data['access_token'])) {
            return new AccessToken($data['access_token'], $data['token_type'], $data);
        } else {
            throw new AccessTokenResponseException($response);
        }
    }

}