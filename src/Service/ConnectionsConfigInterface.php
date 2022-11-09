<?php


namespace UPCPaymentBundle\Service;


interface ConnectionsConfigInterface
{
    public function isFile(): bool;

    public function getSignatureKey(): string;

    public function getConnectionKey(): string;

    public function getConnectionBaseUrl(): string;

    public function getBasePaymentConfig(): array;
}