<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreramerino\RSQueueBundle\Services;

use Predis\Client as RedisClient;
use Mmoreramerino\RSQueueBundle\Exception\InvalidQueueNameException;

/**
 * Common Producer
 * 
 * This class is defined just to add single jobs inside queues.
 */
class Producer
{

    /**
     * @var Predis\Client
     * 
     * Redis client used to interact with redis service
     */
    private $redis;


    /**
     * @var Array
     * 
     * Queue names configured in config file
     */
    private $queueNames;


    /**
     * @var SerializerInterface
     * 
     * Serializer
     */
    private $serializer;


    /**
     * @param Predis\Client       $redis      Redis object
     * @param Array               $queueNames Queue names array
     * @param SerializerInterface $serializer Serializer
     * 
     * Construct method
     */
    public function __construct(RedisClient $redis, Array $queueNames, SerializerInterface $serializer)
    {
        $this->redis = $redis;
        $this->queueNames = $queueNames;
        $this->serializer = $serializer;
    }


    /**
     * Enqueues whatever inside desired queue
     * 
     * @param String $queue_name Name of queue to enqueue this job
     * @param Mixed  $payload    Data to store inside Job
     * 
     * @return Producer self Object
     */
    public function enqueue($payload, $queueName)
    {
        if (!isset($this->queueNames[$queueName])) {

            throw new InvalidQueueNameException;
        }

        $this->redis->rpush(
            $this->queueNames[$queueName], 
            $this->serializer->apply($payload)
        );

        return $this;
    }


    /**
     * publish whatever inside desired channel
     * 
     * @param String $queue_name Name of queue to enqueue this job
     * @param Mixed  $payload    Data to store inside Job
     * 
     * @return Producer self Object
     */
    public function publish($payload, $channel)
    {
        if (!isset($this->queueNames[$queueName])) {

            throw new InvalidQueueNameException;
        }

        $this->redis->publish(
            $this->queueNames[$queueName], 
            $this->serializer->apply($payload)
        );

        return $this;
    }
}