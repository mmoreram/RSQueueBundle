<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Services\Abstracts;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Predis\Client as RedisClient;
use Mmoreram\RSQueueBundle\Exception\InvalidQueueNameException;
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
     * @EventDispatcher
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
    public function __construct(EventDispatcher $eventDispatcher, RedisClient $redis, QueueAliasResolver $queueAliasResolver, SerializerInterface $serializer)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->redis = $redis;
        $this->queueAliasResolver = $queueAliasResolver;
        $this->serializer = $serializer;
    }
}