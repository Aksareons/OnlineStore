<?php
namespace App\Domain\Product;


use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Shared\Money;


#[ORM\Entity]
#[ORM\Table(name: "products")]
#[ORM\UniqueConstraint(name: "unique_product_name", columns: ["name"])]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: "product_id")]
    private ProductId $id;

    #[ORM\Column(type: "product_name")]
    private ProductName $name;

    #[ORM\Embedded(class: Money::class)]
    private Money $price;

    #[ORM\Column(type: "boolean")]
    private bool $isWeighted;

    private function __construct(ProductId $id, ProductName $name, Money $price, bool $isWeighted)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->isWeighted = $isWeighted;
    }

    public static function create(ProductId $id, ProductName $name, Money $price, bool $isWeighted): self
    {
        return new self($id, $name, $price, $isWeighted);
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function isWeighted(): bool
    {
        return $this->isWeighted;
    }

    public function name(): ProductName
    {
        return $this->name;
    }
}
