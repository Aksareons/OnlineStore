<?php

namespace App\Tests;
use App\Domain\Product\Service\ProductCreationService;
use PHPUnit\Framework\TestCase;
use App\Domain\Shared\Money;
use App\Infrastructure\InMemory\InMemoryProductRepository;

final class ProductDomainTest extends TestCase
{
    public function testUniqProductName(): void
    {
        $productRepo = new InMemoryProductRepository();

        $productService = new ProductCreationService($productRepo);
        $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);
        try {
            $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        }catch (\DomainException $e) {
            $this->assertEquals('Product with this name already exists', $e->getMessage());
        }
        $this->assertCount(1, $productRepo->findAll());

    }

    public function testRequiredFieldsForProduct(): void
    {
        $productRepo = new InMemoryProductRepository();
        $productService = new ProductCreationService($productRepo);
        try {
            $productService->createProduct('', Money::fromFloat(10.0)->cents(), false);
        }catch (\InvalidArgumentException $e) {
            $this->assertEquals('ProductName cannot be empty', $e->getMessage());
        }
        $this->assertCount(0, $productRepo->findAll());

    }
}
