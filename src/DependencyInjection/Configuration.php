<?php

declare(strict_types=1);

namespace PChapl\DoctrineIdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('doctrine_id');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('types')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ?->end()
            ?->end();

        return $treeBuilder;
    }
}
