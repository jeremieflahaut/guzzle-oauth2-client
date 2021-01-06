<?php

namespace JFlahaut\GuzzleOauth2Client\GrantType;


use JFlahaut\GuzzleOauth2Client\AccessToken;

interface GrandTypeInterface
{
    /**
     * @return string
     */
    public function getGrandType(): string;

    /**
     * @return AccessToken|null
     */
    public function getToken(): ?AccessToken;


}