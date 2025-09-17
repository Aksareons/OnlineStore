<?php

namespace App\Domain\Order\Service;

use App\Domain\Order\Order;
use App\Domain\Order\OrderRepositoryInterface;
use App\Domain\Order\ValueObject\OrderId;
use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Shared\Money;

class OrderCreationService
{
    public function __construct(private OrderRepositoryInterface $repo) {}

    public function createOrder(OrderId $id): Order
    {

        if ($this->repo->find($id->value())) {
            throw new \DomainException('Order with this id already exists');
        }

        $order = new Order($id);

        $this->repo->save($order);
        return $order;
    }
}
