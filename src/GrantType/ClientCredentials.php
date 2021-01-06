<?php

namespace JFlahaut\GuzzleOauth2Client\GrantType;


class ClientCredentials extends GrandType
{
    protected $grantType = 'client_credentials';

    protected function getRequired(): array
    {
        return ['client_id', 'client_secret'];
    }
}
