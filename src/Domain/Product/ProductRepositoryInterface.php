<?php
namespace App\Domain\Product;

use App\Domain\Product\Product;
interface ProductRepositoryInterface
{
    public function save(Product $product): void;
    public function findByName(string $name): ?Product;
    public function findAll(): array;
}
