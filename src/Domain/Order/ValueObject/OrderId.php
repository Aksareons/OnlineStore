<?php
namespace App\Domain\Order\ValueObject;

final class OrderId
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) throw new \InvalidArgumentException('OrderId cannot be empty');
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public function equals(OrderId $other): bool
    {
        return $this->value === $other->value();
    }

    public static function generate(): self
    {
        return new self(uniqid('order_'));
    }
}
