<?php
namespace App\Domain\Order;


use App\Domain\Order\Payment\PaymentMethodInterface;
use App\Domain\Order\ValueObject\InvoiceId;
use App\Domain\Order\ValueObject\InvoiceStatus;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Shared\Money;


#[ORM\Entity]
#[ORM\Table(name: "invoices")]
class Invoice implements PaymentMethodInterface
{
    #[ORM\Id]
    #[ORM\Column(type: "invoice_id")]
    private InvoiceId $id;


    #[ORM\Embedded(class: Money::class)]
    private Money $amount;


    #[ORM\Column(type: "invoice_status")]
    private InvoiceStatus $status;


    public function __construct(InvoiceId $id, Money $amount)
    {

        $this->id = $id;
        $this->amount = $amount;
        $this->status = InvoiceStatus::new();

    }


    public function isNew(): bool
    {
        return $this->status->isNew();
    }

    public function isPaid(): bool
    {
        return $this->status->isPaid();
    }

    public function cancel(): void
    {
        if ($this->isPaid()) throw new \DomainException('Cannot cancel paid invoice');
        $this->status = InvoiceStatus::cancelled();
    }


    public function pay(Money $paidAmount): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('Invoice not in new state');
        }
        if (!$this->amount->equals($paidAmount)) {
            throw new \DomainException('Paid amount mismatch');
        }
        $this->status = InvoiceStatus::paid();
    }


    public function id(): InvoiceId
    {
        return $this->id;
    }
    public function amount(): Money
    {
        return $this->amount;
    }
    public function status(): InvoiceStatus
    {
        return $this->status;
    }
}
