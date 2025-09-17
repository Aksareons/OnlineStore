<?php

namespace App\Domain\Order;
interface OrderRepositoryInterface
{
    public function save(Order $order): void;

    public function find(string $id): ?Order;

    public function findAll(): array;
}
