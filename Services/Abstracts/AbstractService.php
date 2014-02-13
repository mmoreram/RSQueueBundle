<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Services\Abstracts;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Redis as RedisClient;
use Mmoreram\RSQueueBundle\Serializer\Interfaces\SerializerInterface;
use Mmoreram\RSQueueBundle\Resolver\QueueAliasResolver;

/**
 * Abstract service
 *
 * Provides base structure of all rsqueue services
 */
class AbstractService
{
    /**
     * @var EventDispatcherInterface
     *
     * EventDispatcher instance
     */
    protected $eventDispatcher;

    /**
     * @var Predis\Client
     *
     * Redis client used to interact with redis service
     */
    protected $redis;

    /**
     * @var QueueAliasResolver
     *
     * Queue alias resolver
     */
    protected $queueAliasResolver;

    /**
     * @var SerializerInterface
     *
     * Serializer
     */
    protected $serializer;

    /**
     * @param EventDispatcher     $eventDispatcher    EventDispatcher instance
     * @param Predis\Client       $redis              Redis instance
     * @param QueueAliasResolver  $queueAliasResolver Resolver for queue alias
     * @param SerializerInterface $serializer         Serializer instance
     *
     * Construct method
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, RedisClient $redis, QueueAliasResolver $queueAliasResolver, SerializerInterface $serializer)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->redis = $redis;
        $this->queueAliasResolver = $queueAliasResolver;
        $this->serializer = $serializer;
    }
}
