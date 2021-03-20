<?php

declare(strict_types=1);

namespace pchapl\DoctrineIdBundle\Id;

use JetBrains\PhpStorm\Pure;

abstract class Base
{
    private function __construct(private string $id)
    {
    }

    public function getValue(): string
    {
        return $this->id;
    }

    #[Pure]
    public static function fromValue(string $value): static
    {
        return new static($value);
    }

    public static function new(Factory $factory): static
    {
        return new static($factory->generate());
    }
}
