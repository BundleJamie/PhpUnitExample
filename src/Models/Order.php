<?php

namespace App\Models;

class Order
{
    private string $id;
    private string $customerEmail;
    private array $items;
    private float $totalAmount;
    private string $status;
    private ?string $transactionId;

    public function __construct(
        string $customerEmail,
        array $items,
        float $totalAmount
    ) {
        $this->id = uniqid('order_', true);
        $this->customerEmail = $customerEmail;
        $this->items = $items;
        $this->totalAmount = $totalAmount;
        $this->status = 'pending';
        $this->transactionId = null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

}