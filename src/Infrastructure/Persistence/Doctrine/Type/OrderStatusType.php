<?php
namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Order\ValueObject\InvoiceStatus;
use App\Domain\Order\ValueObject\OrderStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class OrderStatusType extends StringType
{
    public const NAME = 'invoice_status';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderStatus
    {
        return $value === null ? null : OrderStatus::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof OrderStatus ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
