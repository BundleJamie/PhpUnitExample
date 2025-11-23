<?php

namespace App\Interfaces;

interface InventoryServiceInterface
{
    public function checkStock(string $productId): int;
    public function reserveStock(string $productId, int $quantity): bool;
    public function releaseStock(string $productId, int $quantity): bool;
}