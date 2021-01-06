<?php

namespace JFlahaut\GuzzleOauth2Client\GrantType;


use JFlahaut\GuzzleOauth2Client\AccessToken;

interface GrantTypeInterface
{
    /**
     * @return string
     */
    public function getGrantType(): string;

    /**
     * @return AccessToken|null
     */
    public function getToken(): ?AccessToken;


}