<?php

declare(strict_types=1);

namespace pchapl\DoctrineIdBundle;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use pchapl\DoctrineIdBundle\Exception\InvalidConfigurationException;
use pchapl\DoctrineIdBundle\Id\Base;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineIdBundle extends Bundle
{
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

            $instance = $this->instantiate();

            $instance->setClass($class);
            $instance->setName($typeName);

            Type::getTypeRegistry()->register($typeName, $instance);
        }
    }

    private function instantiate(): Id\Type
    {
        return new class extends Id\Type {
            /** @var string|Base $class */
            private string $class;
            private string $name;

            public function convertToPHPValue($value, AbstractPlatform $platform): Base
            {
                if ($value instanceof $this->class) {
                    return $value;
                }

                $class = $this->class;

                return $class::fromValue($value);
            }

            public function setClass(string $class): void
            {
                $this->class = $class;
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): void
            {
                $this->name = $name;
            }
        };
    }
}
