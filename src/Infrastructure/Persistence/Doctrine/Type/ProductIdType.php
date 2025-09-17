<?php
namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Product\ValueObject\ProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ProductIdType extends StringType
{
    public const NAME = 'product_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductId
    {
        return $value === null ? null : new ProductId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ProductId ? $value->value() : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
