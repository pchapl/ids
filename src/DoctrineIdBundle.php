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
    /**
     * @throws Exception|InvalidArgumentException
     */
    public function boot(): void
    {
        $types = $this->container->getParameter('pchapl.doctrine_id.types');

        foreach ($types as $typeName => $class) {
            Type::getTypeRegistry()->register($typeName, TypeFactory::instantiateType($class, $typeName));
        }
    }
}
