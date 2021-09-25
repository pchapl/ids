<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use PChapl\DoctrineIdBundle\Exception\InvalidConfigurationException;
use PChapl\DoctrineIdBundle\Id\TypeFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DoctrineIdBundle extends Bundle
{
    /**
     * @throws Exception
     */
    public function boot(): void
    {
        $idTypes = $this->container->hasParameter('doctrine_id')
            ? $this->container->getParameter('doctrine_id')
            : [];

        if (empty($idTypes['types'])) {
            return;
        }

        $types = $this->container->hasParameter('doctrine.dbal.connection_factory.types')
            ? $this->container->getParameter('doctrine.dbal.connection_factory.types')
            : [];

        foreach ($idTypes['types'] as $typeName => $class) {
            if (array_key_exists($typeName, $types)) {
                throw new InvalidConfigurationException(
                    sprintf(
                        'Duplicate key "%s": type should be described either for %s or %s',
                        $typeName,
                        'parameters.doctrine_id.types',
                        'doctrine.dbal.types',
                    )
                );
            }

            $instance = TypeFactory::instantiateType($class, $typeName);

            Type::getTypeRegistry()->register($typeName, $instance);
        }
    }
}
