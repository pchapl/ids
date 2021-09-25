<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle\Id;

use JetBrains\PhpStorm\Pure;
use Stringable;

abstract class Base implements Stringable
{
    private function __construct(private string $id)
    {
    }

    #[Pure]
    final public function __toString(): string
    {
        return $this->getValue();
    }

    #[Pure]
    final public function getValue(): string
    {
        return $this->id;
    }

    #[Pure]
    final public static function fromValue(string $value): static
    {
        return new static($value);
    }

    final public static function new(Factory $factory): static
    {
        return new static($factory->generate());
    }
}
