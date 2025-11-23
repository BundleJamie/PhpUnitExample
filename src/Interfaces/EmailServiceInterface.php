<?php

namespace App\Interfaces;

use App\Models\Order;

interface EmailServiceInterface
{
    public function sendOrderConfirmation(string $email, Order $order): bool;
    public function sendShippingNotification(string $email, string $trackingNumber): bool;
}