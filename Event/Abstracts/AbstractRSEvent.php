<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Event\Abstracts;

use Symfony\Component\EventDispatcher\Event;
use Predis\Client as RedisClient;


abstract class AbstractRSEvent extends Event
{

    /**
     * @var Mixed
     * 
     * Payload
     */
    protected $payload;


    /**
     * @var Redis
     * 
     * Redis instance
     */
    protected $redis;


    /**
     * Construct method
     * 
     * @param Mixed       $payload Payload
     * @param RedisClient $redis   Redis instance
     */
    public function __construct($payload, RedisClient $redis)
    {
        $this->payload = $payload;
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
     * Return redis instance
     * 
     * @return Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }
}
