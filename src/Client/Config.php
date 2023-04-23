<?php


namespace SpeedrunApi\Client;


class Config
{
    public function __construct(
        private ?string $apiKey = null,
        private ?float $timeOut = null
    ) {}

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function getTimeOut(): ?float
    {
        return $this->timeOut;
    }
}