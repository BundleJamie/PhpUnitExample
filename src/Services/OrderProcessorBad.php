<?php

namespace App\Services;

class OrderProcessorBad
{
    public function getUserDiscount(): float
    {
        // Direct dependency on $_SESSION
        $userType = $_SESSION['user_type'] ?? 'regular';

        return match($userType) {
            'premium' => 0.20,
            'vip' => 0.30,
            default => 0.0
        };
    }

    public function shouldApplyTax(): bool
    {
        // Direct dependency on environment variable
        $country = getenv('SHOP_COUNTRY') ?: 'US';

        return in_array($country, ['US', 'CA', 'EU']);
    }

    public function calculateFinalPrice(float $basePrice): float
    {
        $discount = $this->getUserDiscount();
        $price = $basePrice * (1 - $discount);

        if ($this->shouldApplyTax()) {
            $taxRate = 0.10; // 10% tax
            $price = $price * (1 + $taxRate);
        }

        return round($price, 2);
    }
}