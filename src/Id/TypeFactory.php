<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle\Id;

final class TypeFactory
{
    public static function instantiateType(string $class, string $name): Type
    {
        $instance = new class extends Type {
        };

        return $instance->with($class, $name);
    }
}
