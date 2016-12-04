<?php

namespace Mmoreram\RSQueueBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RSQueueExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'rs_queue.queues',
            $config['queues']
        );

        $container->setParameter(
            'rs_queue.serializer.class',
            $config['serializer']
        );

        $container->setParameter(
            'rs_queue.server.redis',
            $config['server']['redis']
        );

        $rsQueueRedisClass = '\Mmoreram\RSQueueBundle\Redis\RedisAdapter';

        if($config['server']['redis']['driver'] === 'predis') {
            $rsQueueRedisClass = '\Mmoreram\RSQueueBundle\Redis\PredisClientAdapter';
        }
        $container->setParameter(
            'rs_queue.redis.class',
            $rsQueueRedisClass
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // BC sf < 2.6
        $definition = $container->getDefinition('rs_queue.serializer');
        if (method_exists($definition, 'setFactory')) {
            $definition->setFactory(array(new Reference('rs_queue.serializer.factory'), 'get'));
        } else {
            $definition->setFactoryService('rs_queue.serializer.factory');
            $definition->setFactoryMethod('get');
        }
        // BC sf < 2.6
        $definition = $container->getDefinition('rs_queue.redis');
        if (method_exists($definition, 'setFactory')) {
            $definition->setFactory(array(new Reference('rs_queue.redis.factory'), 'get'));
        } else {
            $definition->setFactoryService('rs_queue.redis.factory');
            $definition->setFactoryMethod('get');
        }
    }
}
