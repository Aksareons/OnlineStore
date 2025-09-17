<?php
namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Product\ValueObject\ProductName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ProductNameType extends StringType
{
    public const NAME = 'product_name';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductName
    {
        return $value === null ? null : new ProductName($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ProductName ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
