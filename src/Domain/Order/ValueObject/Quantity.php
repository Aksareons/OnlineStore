<?php
namespace App\Domain\Order\ValueObject;


class Quantity
{
    private float|int $value;
    private bool $isWeighted;


    public function __construct(int|float $value, bool $isWeighted)
    {
        if ($value <= 0) throw new \InvalidArgumentException('Quantity must be positive');
        $this->value = $value;
        $this->isWeighted = $isWeighted;
    }


    public function value(): float|int { return $this->value; }
    public function isWeighted(): bool { return $this->isWeighted; }
}
