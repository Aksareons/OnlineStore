<?php
namespace App\Domain\Product\ValueObject;

final class ProductId
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) throw new \InvalidArgumentException('ProductId cannot be empty');
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public function equals(ProductId $other): bool
    {
        return $this->value === $other->value();
    }

    public static function generate(): self
    {
        return new self(uniqid('prod_'));
    }
}
