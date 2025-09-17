<?php

namespace App\Domain\Product\Service;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;

use App\Domain\Shared\Money;

class ProductCreationService
{
    public function __construct(private ProductRepositoryInterface $repo) {}

    public function createProduct(string $name, float $price, bool $isWeighted): Product
    {
        $productName = new ProductName($name);

        if ($this->repo->findByName($productName->value())) {
            throw new \DomainException('Product with this name already exists');
        }

        $product = Product::create(
            ProductId::generate(),
            $productName,
            new Money($price),
            $isWeighted
        );

        $this->repo->save($product);

        return $product;
    }
}
