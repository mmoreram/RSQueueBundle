<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Services\Abstracts;

use Mmoreram\RSQueueBundle\Redis\AdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var AdapterInterface
     *
     * Redis client used to interact with redis service
     */
    protected $redisAdapter;

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
     * @param EventDispatcherInterface $eventDispatcher    EventDispatcher instance
     * @param AdapterInterface    $redisAdapter           Redis adapter instance
     * @param QueueAliasResolver       $queueAliasResolver Resolver for queue alias
     * @param SerializerInterface      $serializer         Serializer instance
     *
     * Construct method
     */
    public function __construct(EventDispatcherInterface $eventDispatcher,AdapterInterface $redisAdapter, QueueAliasResolver $queueAliasResolver, SerializerInterface $serializer)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->redisAdapter = $redisAdapter;
        $this->queueAliasResolver = $queueAliasResolver;
        $this->serializer = $serializer;
    }
}
