<?php
namespace App\Domain\Shared;

final class Money
{
    private int $cents;

    public function __construct(int $cents)
    {
        if ($cents < 0) {
            throw new \InvalidArgumentException('Money cannot be negative');
        }
        $this->cents = $cents;
    }

    public static function fromFloat(float $amount): self
    {
        return new self((int) round($amount * 100));
    }

    public function add(Money $other): Money
    {
        return new self($this->cents + $other->cents);
    }

    public function cents(): int { return $this->cents; }

    public function equals(Money $other): bool
    {
        return $this->cents === $other->cents;
    }
}
