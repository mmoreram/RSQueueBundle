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
 * Common Consumer
 * 
 * This class is defined just to retrieve
 */
class Consumer
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
     * Retrieve queue value, with a defined timeout
     * 
     * @param String  $queueName Name of queue to enqueue this job
     * @param Integer $timeout   Timeout
     * 
     * @return Mixed payload unserialized
     */
    public function retrieve($queueName, $timeout)
    {
        if (!isset($this->queueNames[$queueName])) {

            throw new InvalidQueueNameException;
        }

        return $this->serializer->revert(
            $this->redis->rpop(
                $this->queueNames[$queueName], 
                $timeout
            )
        );
    }
}