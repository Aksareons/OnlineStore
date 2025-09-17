<?php
namespace App\Domain\Order;


use App\Domain\Order\ValueObject\InvoiceId;
use App\Domain\Order\ValueObject\OrderId;
use App\Domain\Order\ValueObject\OrderStatus;
use App\Domain\Order\ValueObject\Quantity;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Product\Product;
use App\Domain\Shared\Money;


#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: "order_id")]
    private OrderId $id;

    #[ORM\Column(type: "order_status")]
    private OrderStatus $status;


    #[ORM\OneToMany(targetEntity: OrderLine::class, mappedBy: "order", cascade: ["persist"], orphanRemoval: true)]
    private array $lines = [];


    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: "order", cascade: ["persist"], orphanRemoval: true)]
    private array $invoices = [];


    public function __construct(OrderId $id)
    {
        $this->id = $id;
        $this->status = OrderStatus::new();

    }

    public function markInvoiced(): void
    {
        $this->status = OrderStatus::invoiced();
    }

    public function markPaid(): void
    {
        $this->status = OrderStatus::paid();
    }

    public function addLine(Product $product, Quantity $quantity): void
    {
        if (!empty($this->invoices)) {
            throw new \DomainException('Cannot modify order after invoice');
        }
        if (!$product->isWeighted() && (int)$quantity->value() !== $quantity->value()) {
            throw new \InvalidArgumentException('Quantity must be integer for non-weighted products');
        }
        $this->lines[] = new OrderLine($this, $product->id(), $product->price(), $quantity, $product->isWeighted());
    }


    public function total(): Money
    {
        $sum = new Money(0);
        /** @var OrderLine $l */
        foreach ($this->lines as $l) {
            $sum = $sum->add($l->lineTotal());
        }
        return $sum;
    }


    public function issueInvoice(string $invoiceId): Invoice
    {
        /** @var Invoice $inv */
        foreach ($this->invoices as $inv)  {
            if ($inv->isNew()) {
                $inv->cancel();
            }
        }
        $invoice = new Invoice(new InvoiceId($invoiceId), $this->total());
        $this->invoices[] = $invoice;
        $this->markInvoiced();
        return $invoice;
    }


    public function applyPayment(string $invoiceId, Money $amount): void
    {
        /** @var Invoice $inv */
        foreach ($this->invoices as $inv) {
            if ($inv->id()->value() === $invoiceId) {
                $inv->pay($amount);
                if ($inv->isPaid()) {
                    $this->markPaid();
                }
                return;
            }
        }
        throw new \InvalidArgumentException('Invoice not found');
    }


    public function status(): OrderStatus
    {
        return $this->status;
    }
    public function id(): OrderId
    {
        return $this->id;
    }
    public function invoices(): array
    {
        return $this->invoices;
    }
    public function lines(): array
    {
        return $this->lines;
    }
}
