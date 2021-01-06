<?php

namespace JFlahaut\GuzzleOauth2Client\GrantType;


class ClientCredentials extends GrantType
{
    /**
     * @var string
     */
    protected $grantType = 'client_credentials';

    /**
     * @return array
     */
    protected function getRequired(): array
    {
        return ['client_id', 'client_secret'];
    }
}
