<?php
namespace App\Application\Service;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;
use App\Domain\Product\Service\ProductCreationService;

final class ProductService
{
    public function __construct(private ProductCreationService $creationService) {}

    public function createProduct(string $name, float $price, bool $isWeighted): Product
    {
        // Просто координує: делегує всі перевірки та створення
        return $this->creationService->createProduct($name, $price, $isWeighted);
    }
}
