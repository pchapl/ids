<?php

declare(strict_types=1);

namespace pchapl\DoctrineIdBundle\Id;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

abstract class Type extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Base) {
            throw new ConversionException('"value" is not an instance of ' . Base::class);
        }

        return $value->getValue();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
