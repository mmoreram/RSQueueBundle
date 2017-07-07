<?php

namespace Mmoreram\RSQueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class MicroServiceConfiguration
 *
 * @package Mmoreram\RSQueueBundle\DependencyInjection
 */
class MicroServiceConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('microservice');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('name')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
