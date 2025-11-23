<?php

namespace App\Services;

use App\Models\Order;

class OrderValidator
{
    /**
     * Validates email format
     *
     * TODO: Write parameterized tests using data provider
     * Test cases: valid emails, invalid emails, edge cases
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validates order total is positive and not excessive
     *
     * TODO: Write tests with AAA pattern
     * Test cases: negative amounts, zero, normal amounts, very large amounts
     */
    public function validateAmount(float $amount): bool
    {
        return $amount > 0 && $amount <= 100000;
    }

    /**
     * Validates order has at least one item
     *
     * TODO: Write tests for empty and non-empty item arrays
     */
    public function validateItems(array $items): bool
    {
        return !empty($items);
    }

    /**
     * Validates credit card token format
     *
     * TODO: Write parameterized tests
     * Valid format: starts with 'tok_' followed by alphanumeric characters
     */
    public function validateCardToken(string $token): bool
    {
        return preg_match('/^tok_[a-zA-Z0-9]{10,}$/', $token) === 1;
    }

    /**
     * Performs complete order validation
     *
     * TODO: Write integration-style test that validates entire order
     */
    public function validateOrder(Order $order, string $cardToken): array
    {
        $errors = [];

        if (!$this->validateEmail($order->getCustomerEmail())) {
            $errors[] = 'Invalid email address';
        }

        if (!$this->validateAmount($order->getTotalAmount())) {
            $errors[] = 'Invalid order amount';
        }

        if (!$this->validateItems($order->getItems())) {
            $errors[] = 'Order must contain at least one item';
        }

        if (!$this->validateCardToken($cardToken)) {
            $errors[] = 'Invalid payment token';
        }

        return $errors;
    }
}