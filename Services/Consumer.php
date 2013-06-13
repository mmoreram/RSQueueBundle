<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Services;

use Mmoreram\RSQueueBundle\Services\Abstracts\AbstractService;
use Mmoreram\RSQueueBundle\RSQueueEvents;
use Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent;

/**
 * Consumer class
 */
class Consumer extends AbstractService
{

    /**
     * Retrieve queue value, with a defined timeout
     * 
     * @param String  $queueAlias Alias of queue to consume from
     * @param Integer $timeout    Timeout
     * 
     * @return Mixed payload unserialized
     * 
     * @throws InvalidAliasException If any alias is not defined
     */
    public function consume($queueAlias, $timeout)
    {
        $queue = $this->queueAliasResolver->get($queueAlias);
        $payloadArray = $this->redis->blpop($queue, $timeout);
        $payload = $this->serializer->revert($payloadArray[1]);

        /**
         * Dispatching consumer event...
         */
        $consumerEvent = new RSQueueConsumerEvent($payload, $queueAlias, $queue, $this->redis);
        $this->eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_CONSUMER, $consumerEvent);

        return $payload;
    }
}