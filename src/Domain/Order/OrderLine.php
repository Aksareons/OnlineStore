<?php
namespace App\Domain\Order;


use App\Domain\Order\ValueObject\Quantity;
use App\Domain\Product\ValueObject\ProductId;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Shared\Money;


#[ORM\Entity]
#[ORM\Table(name: "order_lines")]
class OrderLine
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;


    #[ORM\ManyToOne(targetEntity: Order::class)]
    private Order $order;


    #[ORM\Column(type: "string")]
    private ProductId $productId;


    #[ORM\Embedded(class: Money::class)]
    private Money $unitPrice;


    #[ORM\Embedded(class: Quantity::class)]
    private Quantity $quantity;


    #[ORM\Column(type: "boolean")]
    private bool $isWeighted;


    public function __construct(Order $order, ProductId $productId, Money $unitPrice, Quantity $quantity, bool $isWeighted)
    {
        $this->id = uniqid('ol_');
        $this->order = $order;
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->isWeighted = $isWeighted;
    }


    public function lineTotal(): Money
    {
        return new Money((int) round($this->unitPrice->cents() * $this->quantity->value()));
    }
}
