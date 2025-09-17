<?php
namespace App\Domain\Product\ValueObject;

final class ProductName
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) throw new \InvalidArgumentException('ProductName cannot be empty');
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public function equals(ProductName $other): bool
    {
        return $this->value === $other->value();
    }
}
