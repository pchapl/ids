<?php

namespace PChapl\Tests;

use PChapl\DoctrineIdBundle\DoctrineIdBundle;
use PChapl\DoctrineIdBundle\Id\Type;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineIdBundleTest extends TestCase
{
    private const TEST_ID_TYPE_NAME = 'test_id_type_name';

    private DoctrineIdBundle $bundle;

    private array $parameters = [
        DoctrineIdBundle::TYPES_PARAMETER_NAME => [
            self::TEST_ID_TYPE_NAME => TestId::class,
        ],
    ];

    protected function setUp(): void
    {
        $this->bundle = new DoctrineIdBundle();
        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $this->bundle->setContainer($container);
        $container->method('getParameter')->willReturnCallback(fn (string $name) => $this->parameters[$name] ?? null);
    }

    public function testBoot(): void
    {
        self::assertFalse(Type::hasType(self::TEST_ID_TYPE_NAME));
        $this->bundle->boot();
        self::assertTrue(Type::hasType(self::TEST_ID_TYPE_NAME));
        self::assertInstanceOf(Type::class, Type::getType(self::TEST_ID_TYPE_NAME));
    }
}
