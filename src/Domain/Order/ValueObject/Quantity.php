<?php
namespace App\Domain\Order\ValueObject;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Quantity
{
    #[Column(type: 'float')]
    private float|int $value;
    #[Column(type: 'boolean')]
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
