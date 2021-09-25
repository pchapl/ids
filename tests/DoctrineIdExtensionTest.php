<?php

namespace PChapl\Tests;

use PChapl\DoctrineIdBundle\DependencyInjection\DoctrineIdExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineIdExtensionTest extends TestCase
{
    private DoctrineIdExtension $ext;

    protected function setUp(): void
    {
        $this->ext = new DoctrineIdExtension();
    }

    public function testLoad(): void
    {
        $this->ext->load([], $this->createMock(ContainerBuilder::class));
        self::assertTrue(true);
    }
}
