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
    public static function fromValue(string $value): self
    {
        return new static($value);
    }

    public static function new(Factory $factory): self
    {
        return new static($factory->generate());
    }
}
