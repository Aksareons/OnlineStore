<?php
namespace App\Domain\Order\ValueObject;

final class InvoiceId
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) throw new \InvalidArgumentException('InvoiceId cannot be empty');
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public function equals(InvoiceId $other): bool
    {
        return $this->value === $other->value();
    }

    public static function generate(): self
    {
        return new self(uniqid('inv_'));
    }
}
