<?php

namespace ezMongo\MongoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ez_mongo');
        $rootNode
            ->children()
                ->arrayNode('connections')
                ->useAttributeAsKey('default_connection')
                ->canBeUnset()
                ->prototype('array')
                    ->children()
                        ->scalarNode('hostname')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue(27017)->end()
                        ->scalarNode('username')->defaultValue('')->end()
                        ->scalarNode('password')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end()
                ->arrayNode('collections')
                ->canBeUnset()
                ->useAttributeAsKey('resourceNameAlias')
                ->prototype('array')
                    ->children()
                        ->scalarNode('collection')->defaultValue('test')->end()
                        ->scalarNode('database')->defaultValue('test')->end()
                        ->scalarNode('connection')->defaultValue('default_connection')->end()
                    ->end()
                ->end()
            ->end()
        ->end();
        return $treeBuilder;
    }
}
