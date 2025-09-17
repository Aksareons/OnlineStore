<?php
namespace App\Infrastructure\InMemory;


use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;


final class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $products = [];


    public function save(Product $product): void
    {
        if (isset($this->products[$product->name()->value()])) {
            throw new \DomainException('Product already exists');
        }
        $this->products[$product->name()->value()] = $product;

    }


    public function findByName(string $name): ?Product
    {
        return $this->products[$name] ?? null;
    }


    public function findAll(): array
    {
        return array_values($this->products);
    }
}
