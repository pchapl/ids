<?php

namespace PChapl\Tests\DependencyInjection;

use PChapl\DoctrineIdBundle\DependencyInjection\DoctrineIdExtension;
use PChapl\DoctrineIdBundle\DoctrineIdBundle;
use PChapl\DoctrineIdBundle\Exception\InvalidConfigurationException;
use PChapl\Tests\TestId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineIdExtensionTest extends TestCase
{
    private const TEST_ID_TYPE_NAME = 'test_id_type_name';
    private const CONFIGS = [
        [
            DoctrineIdBundle::CONFIG_TYPES_KEY => [
                self::TEST_ID_TYPE_NAME => TestId::class,
            ],
        ],
    ];

    /** @var MockObject&ContainerBuilder */
    private MockObject|ContainerBuilder $container;
    private DoctrineIdExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new DoctrineIdExtension();

        /** @var ContainerBuilder&MockObject $container */
        $container = $this->createMock(ContainerBuilder::class);
        $this->container = $container;
    }

    public function testLoad(): void
    {
        $this->container
            ->expects($this->once())
            ->method('setParameter')
            ->willReturnCallback(
                static function (string $name, mixed $value) {
                    self::assertSame(DoctrineIdBundle::TYPES_PARAMETER_NAME, $name);
                    self::assertIsArray($value);
                    self::assertCount(1, $value);

                    foreach ($value as $k => $v) {
                        self::assertSame(self::TEST_ID_TYPE_NAME, $k);
                        self::assertSame(TestId::class, $v);
                    }
                }
            );

        $this->extension->load(self::CONFIGS, $this->container);
    }

    public function testLoadFailsOnClass(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Can not load class 'fake-class'");

        $this->extension->load(
            [[DoctrineIdBundle::CONFIG_TYPES_KEY => [self::TEST_ID_TYPE_NAME => 'fake-class']]],
            $this->container,
        );
    }

    public function testLoadFailsOnDuplicate(): void
    {
        $duplicatedId = 'test_duplicated_id';

        $this->container->method('hasParameter')->willReturnCallback(
            static fn (string $name): bool => $name === 'doctrine.dbal.connection_factory.types',
        );
        $this->container->method('getParameter')->willReturnCallback(
            static fn (string $name) => $name === 'doctrine.dbal.connection_factory.types'
                ? [$duplicatedId => TestId::class]
                : null,
        );

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage(
            "Duplicate key \"$duplicatedId\": type should be described either for doctrine_id.types or doctrine.dbal.types"
        );

        $this->extension->load(
            [[DoctrineIdBundle::CONFIG_TYPES_KEY => [$duplicatedId => TestId::class]]],
            $this->container,
        );
    }
}
