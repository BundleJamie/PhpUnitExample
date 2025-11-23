<?php

namespace App\Interfaces;

interface PaymentGatewayInterface
{
    public function charge(float $amount, string $cardToken): bool;
    public function refund(string $transactionId): bool;
}