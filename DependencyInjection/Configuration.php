<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreramerino\RSQueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rs_queue');

        $rootNode
            ->children()
                ->arrayNode('queues')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('serializer')
                    ->defaultValue('Mmoreramerino\RSQueueBundle\Serialization\JsonSerializer')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
