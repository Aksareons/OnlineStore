<?php
namespace App\Domain\Order\ValueObject;

final class InvoiceStatus
{
    private const NEW = 'new';
    private const PAID = 'paid';
    private const CANCELLED = 'cancelled';

    private string $value;

    private function __construct(string $value)
    {
        if (!in_array($value, [self::NEW, self::PAID, self::CANCELLED], true)) {
            throw new \DomainException("Invalid invoice status: $value");
        }
        $this->value = $value;
    }

    public static function new(): self
    {
        return new self(self::NEW);
    }

    public static function paid(): self
    {
        return new self(self::PAID);
    }

    public static function cancelled(): self
    {
        return new self(self::CANCELLED);
    }

    public function isNew(): bool
    {
        return $this->value === self::NEW;
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID;
    }

    public function isCancelled(): bool
    {
        return $this->value === self::CANCELLED;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::NEW, self::PAID, self::CANCELLED], true)) {
            throw new \DomainException("Invalid invoice status: $value");
        }
        return new self($value);
    }
}
