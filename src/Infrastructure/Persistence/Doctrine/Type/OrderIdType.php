<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Order\ValueObject\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class OrderIdType extends StringType
{
    public const NAME = 'order_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderId
    {
        return $value === null ? null : new OrderId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof OrderId ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
