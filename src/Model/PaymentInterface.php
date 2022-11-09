<?php

declare(strict_types=1);

namespace UPCPaymentBundle\Model;

interface PaymentInterface
{
    public function getId(): ?int;

    public function paymentSuffix(): ?string;

    public function getAmount(): null|int|float;
}