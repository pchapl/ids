<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use PChapl\DoctrineIdBundle\Id\TypeFactory;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DoctrineIdBundle extends Bundle
{
    public const TYPES_PARAMETER_NAME = 'pchapl.doctrine_id.types';
    public const BUNDLE_ID = 'doctrine_id';
    public const CONFIG_TYPES_KEY = 'types';

    /** @throws Exception|InvalidArgumentException */
    public function boot(): void
    {
        $types = $this->container->getParameter(self::TYPES_PARAMETER_NAME);

        foreach ($types as $typeName => $class) {
            if (!Type::hasType($typeName)) {
                Type::getTypeRegistry()->register($typeName, TypeFactory::instantiateType($class, $typeName));
            }
        }
    }
}
