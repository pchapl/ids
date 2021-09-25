<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle\DependencyInjection;

use PChapl\DoctrineIdBundle\DoctrineIdBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(DoctrineIdBundle::BUNDLE_ID);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode(DoctrineIdBundle::CONFIG_TYPES_KEY)
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ?->end()
            ?->end();

        return $treeBuilder;
    }
}
