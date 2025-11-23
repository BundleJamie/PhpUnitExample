<?php

namespace App\Interfaces;

interface LoggerInterface
{
    public function info(string $message, array $context = []): void;
    public function error(string $message, array $context = []): void;
}