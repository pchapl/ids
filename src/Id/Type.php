<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle\Id;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use JetBrains\PhpStorm\Pure;
use PChapl\DoctrineIdBundle\Exception\ConversionException;

abstract class Type extends StringType
{
    /** @var class-string<Base> $class */
    private string $class;
    private string $name;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Base) {
            return $value->getValue();
        }

        throw new ConversionException('"value" is not an instance of ' . Base::class);
    }

    #[Pure]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Base
    {
        if ($value === null || $value instanceof $this->class) {
            return $value;
        }

        $class = $this->class;

        return $class::fromValue($value);
    }

    #[Pure]
    public function getName(): string
    {
        return $this->name;
    }

    #[Pure]
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function with(string $class, string $name): static
    {
        $new = clone $this;
        $new->class = $class;
        $new->name = $name;

        return $new;
    }
}
