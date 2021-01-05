# guzzle-oauth2-client

source: Sainsburys / guzzle-oauth2-plugin

### Example

```php

    use GuzzleHttp\Client;
    use JFlahaut\GuzzleOauth2Client\GrantType\ClientCredentials;
    use JFlahaut\GuzzleOauth2Client\Middleware\OauthMiddleware;
    use JFlahaut\GuzzleOauth2Client\Oauth2Client;


    $baseUri = 'https://api.example.com';

    $config = [
        'client_id' => 'xxxxx',
        'client_secret' => 'xxxxx',
        'token_url' => '/oauth/token',
        'auth_location' => 'json'
    ];

    $oauthClient = new Client([
        'base_uri' => $baseUri,
    ]);

    $grantType = new ClientCredentials($oauthClient, $config);
    $middleware = new OauthMiddleware($oauthClient, $grantType);

    $client = Oauth2Client::create($middleware, [
        'base_uri' => $baseUri,
    ]);


```

