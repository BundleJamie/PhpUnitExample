<?php

namespace App\Models;

class OrderItem
{
    private string $productId {
        get {
            return $this->productId;
        }
    }
    private string $productName {
        get {
            return $this->productName;
        }
    }
    private int $quantity {
        get {
            return $this->quantity;
        }
    }
    private float $price {
        get {
            return $this->price;
        }
    }

    public function __construct(
        string $productId,
        string $productName,
        int $quantity,
        float $price
    ) {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getTotal(): float
    {
        return $this->quantity * $this->price;
    }
}