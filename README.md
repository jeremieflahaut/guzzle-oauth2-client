# guzzle-oauth2-client

source: Sainsburys / guzzle-oauth2-plugin

### Example

```php

    $baseUri = 'https://coreg.r1a.eu';

    $config = [
        'client_id' => '2',
        'client_secret' => 'amPi2xX4ACTHpb7x59AI9cUWy427p40ByqlE4JrN',
        'token_url' => '/oauth/token',
        'auth_location' => 'json'
    ];

    $oauthClient = new \GuzzleHttp\Client([
        'base_uri' => $baseUri,
    ]);

    $grantType = new ClientCredentials($oauthClient, $config);
    $middleware = new OauthMiddleware($oauthClient, $grantType);

    $client = Oauth2Client::create($middleware, [
        'base_uri' => $baseUri,
    ]);

    try {
        $client->postUser([
            'email' => 'testadv2@test.fr',
            'civility' => 'm',
            'lastname' => 'Testb',
            'firstname' => 'Testa',
            'birthdate' => '1975-01-01',
            'src' => 'croixrouge'
        ]);
    } catch (ClientException $e) {
        dd(Utils::jsonDecode($e->getResponse()->getBody()));
    } catch (\Exception $e) {
        
    }

```

