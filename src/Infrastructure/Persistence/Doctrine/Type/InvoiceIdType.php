<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Order\ValueObject\InvoiceId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class InvoiceIdType extends StringType
{
    public const NAME = 'invoice_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?InvoiceId
    {
        return $value === null ? null : new InvoiceId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof InvoiceId ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
