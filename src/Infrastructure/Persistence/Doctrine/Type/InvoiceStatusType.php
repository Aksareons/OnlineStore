<?php
namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Order\ValueObject\InvoiceStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class InvoiceStatusType extends StringType
{
    public const NAME = 'invoice_status';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?InvoiceStatus
    {
        return $value === null ? null : InvoiceStatus::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof InvoiceStatus ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
