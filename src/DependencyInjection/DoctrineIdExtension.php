<?php

namespace PChapl\DoctrineIdBundle\DependencyInjection;

use PChapl\DoctrineIdBundle\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class DoctrineIdExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $idTypes = $config['types'] ?? [];

        $types = $container->hasParameter('doctrine.dbal.connection_factory.types')
            ? $container->getParameter('doctrine.dbal.connection_factory.types')
            : [];

        foreach ($idTypes as $typeName => $class) {
            if (!class_exists($class)) {
                throw new InvalidConfigurationException("Can not load class $class");
            }
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
        }

        $container->setParameter('pchapl.doctrine_id.types', $idTypes);
    }
}
