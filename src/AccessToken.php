<?php

namespace JFlahaut\GuzzleOauth2Client;


use DateInterval;
use DateTime;
use Exception;
use JFlahaut\GuzzleOauth2Client\Exceptions\ExpirationTokenException;

class AccessToken
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var DateTime
     */
    protected $expires;

    /**
     * AccessToken constructor.
     *
     * @param string $token
     * @param string $type
     * @param array $data
     * @throws Exception
     */
    public function __construct(string $token, string $type, array $data = [])
    {
        $this->token = $token;
        $this->type = $type;
        $this->data = $data;

        try {

            if (isset($data['expires'])) {
                $this->expires = new \DateTime();
                $this->expires->setTimestamp($data['expires']);
            } elseif (isset($data['expires_in'])) {
                $this->expires = new DateTime();
                $this->expires->add(new DateInterval(sprintf('PT%sS', $data['expires_in'])));
            }

        } catch (Exception $e) {
            throw new ExpirationTokenException($e->getMessage());
        }

    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires !== null && $this->expires->getTimestamp() < time();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

}