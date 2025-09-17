<?php
namespace App\Infrastructure\InMemory;


use App\Domain\Order\Order;
use App\Domain\Order\OrderRepositoryInterface;


final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    private array $orders = [];


    public function save(Order $order): void
    {
        $this->orders[$order->id()->value()] = $order;
    }


    public function find(string $id): ?Order
    {
        return $this->orders[$id] ?? null;
    }


    public function findAll(): array
    {
        return array_values($this->orders);
    }
}
