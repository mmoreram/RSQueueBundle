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
     * @param Integer $timeout    Timeout. By default, 0
     *
     * @return Mixed payload unserialized
     *
     * @throws InvalidAliasException If any alias is not defined
     */
    public function consume($queueAlias, $timeout = 0)
    {
        $queue = $this->queueAliasResolver->get($queueAlias);
        $payloadArray = $this->redis->blpop($queue, $timeout);
        $payloadSerialized = $payloadArray[1];
        $payload = $this->serializer->revert($payloadSerialized);

        /**
         * Dispatching consumer event...
         */
        $consumerEvent = new RSQueueConsumerEvent($payload, $payloadSerialized, $queueAlias, $queue, $this->redis);
        $this->eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_CONSUMER, $consumerEvent);

        return $payload;
    }
}