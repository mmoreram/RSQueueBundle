<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Event\Abstracts;

use Symfony\Component\EventDispatcher\Event;
use Redis as RedisClient;

/**
 * Abstract event
 */
abstract class AbstractRSEvent extends Event
{

    /**
     * @var Mixed
     *
     * Payload
     */
    protected $payload;

    /**
     * @var String
     *
     * Payload serialized
     */
    protected $payloadSerialized;

    /**
     * @var Redis
     *
     * Redis instance
     */
    protected $redis;

    /**
     * Construct method
     *
     * @param Mixed  $payload           Payload
     * @param String $payloadSerialized Payload serialized
     * @param Redis  $redis             Redis instance
     */
    public function __construct($payload, $payloadSerialized, RedisClient $redis)
    {
        $this->payload = $payload;
        $this->payloadSerialized = $payloadSerialized;
        $this->redis = $redis;
    }

    /**
     * Return payload
     *
     * @return Mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Return payload serialized
     *
     * @return string
     */
    public function getPayloadSerialized()
    {
        return $this->payloadSerialized;
    }

    /**
     * Return redis instance
     *
     * @return Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }
}
