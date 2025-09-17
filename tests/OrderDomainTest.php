<?php

namespace App\Tests;
use App\Domain\Order\Service\OrderCreationService;
use App\Domain\Order\ValueObject\OrderId;
use App\Domain\Product\Service\ProductCreationService;
use DomainException;
use PHPUnit\Framework\TestCase;
use App\Domain\Shared\Money;
use App\Domain\Order\ValueObject\Quantity;
use App\Infrastructure\InMemory\InMemoryProductRepository;
use App\Infrastructure\InMemory\InMemoryOrderRepository;


final class OrderDomainTest extends TestCase
{
    public function testAddLineAndIssueInvoice(): void
    {
        $orderRepo = new InMemoryOrderRepository();
        $productRepo = new InMemoryProductRepository();
        $orderCreationService = new OrderCreationService($orderRepo);
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(2, true));


        $invoice = $order->issueInvoice('i1');


        $this->assertEquals('invoiced', $order->status()->value());
        $this->assertEquals(20.0, $invoice->amount()->cents() / 100);


        $orderRepo->save($order);
        $this->assertSame($order, $orderRepo->find($order->id()->value()));
    }

    public function testAddMultiLineAndIssueInvoice(): void
    {
        $orderRepo = new InMemoryOrderRepository();
        $productRepo = new InMemoryProductRepository();

        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);
        $product2 = $productService->createProduct('Product 2', Money::fromFloat(10.0)->cents(), false);
        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(2, true));
        $order->addLine($product2, new Quantity(2, true));


        $invoice = $order->issueInvoice('i1');


        $this->assertEquals('invoiced', $order->status()->value());
        $this->assertEquals(40.0, $invoice->amount()->cents() / 100);


        $orderRepo->save($order);
        $this->assertSame($order, $orderRepo->find($order->id()->value()));

    }


    public function testInvoicePayment(): void
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(1, true));
        $invoice = $order->issueInvoice('i2');


        $order->applyPayment('i2', Money::fromFloat(10.0));
        $this->assertEquals('paid', $invoice->status()->value());
        $this->assertEquals('paid', $order->status()->value());
    }

    public function testCheckOrderStatus()
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(2, true));

        $this->assertEquals('new', $order->status()->value());
        $order->issueInvoice('i2');
        $this->assertEquals('invoiced', $order->status()->value());
    }

    public function testCantAddedItemsAfterInvoice()
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(2, true));

        $order->issueInvoice('i2');
        try {
            $order->addLine($product, new Quantity(2, true));

        }catch (\DomainException $e) {
            $this->assertEquals('Cannot modify order after invoice', $e->getMessage());
        }
        $this->assertCount(1, $order->lines());
    }


    public function testIsWeightedProduct(): void
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), true);

        $this->assertCount(1, $productRepo->findAll());

        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(0.5, true));
        $invoice = $order->issueInvoice('i1');


        $this->assertEquals('invoiced', $order->status()->value());
        $this->assertEquals(5.0, $invoice->amount()->cents() / 100);
    }

    public function testBadIsWeightedProduct(): void
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $this->assertCount(1, $productRepo->findAll());
        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        try {
            $order->addLine($product, new Quantity(0.5, true));

        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Quantity must be integer for non-weighted products', $e->getMessage());
        }
        $this->assertCount(0,  $order->lines());
    }


    public function testMultiInvoicesForOrder(): void
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $this->assertCount(1, $productRepo->findAll());

        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());

        $order->addLine($product, new Quantity(5, true));
        $invoice = $order->issueInvoice('i1');
        $invoice2 = $order->issueInvoice('i2');
        $this->assertEquals($invoice->status()->value(), 'cancelled');;
        $this->assertEquals($invoice2->status()->value(), 'new');;

        $this->assertEquals('invoiced', $order->status()->value());
    }

    public function testPaymentDuplicateForInvoice(): void
    {
        $productRepo = new InMemoryProductRepository();
        $orderRepo = new InMemoryOrderRepository();
        $productService = new ProductCreationService($productRepo);
        $product = $productService->createProduct('Product 1', Money::fromFloat(10.0)->cents(), false);

        $this->assertCount(1, $productRepo->findAll());

        $orderCreationService = new OrderCreationService($orderRepo);
        $order = $orderCreationService->createOrder(OrderId::generate());
        $order->addLine($product, new Quantity(5, true));
        $invoice = $order->issueInvoice('i1');

        $order->applyPayment($invoice->id()->value(), $order->total());
        $this->assertEquals('paid', $order->status()->value());
        try {
            $order->applyPayment($invoice->id()->value(), $order->total());

        }catch (DomainException $e) {
            $this->assertEquals('Invoice not in new state', $e->getMessage());
        }
        $this->assertEquals('paid', $order->status()->value());

    }
}
