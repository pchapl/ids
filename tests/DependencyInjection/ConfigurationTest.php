<?php

namespace PChapl\Tests\DependencyInjection;

use PChapl\DoctrineIdBundle\DependencyInjection\Configuration;
use PChapl\DoctrineIdBundle\DoctrineIdBundle;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    private const TYPES = [
        DoctrineIdBundle::CONFIG_TYPES_KEY => [
            'account_id' => 'App\Data\AccountId',
            'user_id' => 'App\Data\UserId',
        ],
    ];

    private Configuration $configuration;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
    }

    public function testTree(): void
    {
        $tree = $this->configuration->getConfigTreeBuilder()->buildTree();

        self::assertSame(DoctrineIdBundle::BUNDLE_ID, $tree->getName());
        self::assertSame(self::TYPES, $tree->normalize(self::TYPES));
    }
}
