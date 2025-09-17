<?php
namespace App\Domain\Order\ValueObject;

final class OrderStatus
{
    private const NEW = 'new';
    private const INVOICED = 'invoiced';
    private const PAID = 'paid';

    private string $value;

    private function __construct(string $value)
    {
        if (!in_array($value, [self::NEW, self::INVOICED, self::PAID], true)) {
            throw new \DomainException("Invalid order status: $value");
        }
        $this->value = $value;
    }

    public static function new(): self
    {
        return new self(self::NEW);
    }

    public static function invoiced(): self
    {
        return new self(self::INVOICED);
    }

    public static function paid(): self
    {
        return new self(self::PAID);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID;
    }

    public function isInvoiced(): bool
    {
        return $this->value === self::INVOICED;
    }

    public function isNew(): bool
    {
        return $this->value === self::NEW;
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::NEW, self::INVOICED, self::PAID], true)) {
            throw new \DomainException("Invalid order status: $value");
        }
        return new self($value);
    }
}
