<?php


namespace UPCPaymentBundle\Service;


class ConnectionsConfig implements ConnectionsConfigInterface
{
    public bool $isFile = false;

    public function __construct(
        private string $connectionKey,
        private string $signatureKey,
        private string $connectionBaseUrl,
        private array $baseFormConfig,
        private bool $keysInFile
    )
    {

    }

    public function getConnectionKey(): string
    {
        return $this->connectionKey;
    }

    public function getSignatureKey(): string
    {
        return $this->signatureKey;
    }

    public function getConnectionBaseUrl(): string
    {
        return $this->connectionBaseUrl;
    }

    public function isFile(): bool
    {
        return $this->keysInFile;
    }

    public function getBasePaymentConfig(): array
    {
        return $this->baseFormConfig;
    }
}