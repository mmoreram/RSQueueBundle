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
 *
 * This class
 */
class Consumer extends AbstractService
{

    /**
     * Retrieve queue value, with a defined timeout
     *
     * This method accepts a single queue alias or an array with alias
     * Every new element will be popped from one of defined queue
     *
     * Also, new Consumer event is triggered everytime a new element is popped
     *
     * @param Mixed   $queueAlias Alias of queue to consume from ( Can be an array of alias )
     * @param Integer $timeout    Timeout. By default, 0
     *
     * @return Mixed payload unserialized
     *
     * @throws InvalidAliasException If any alias is not defined
     */
    public function consume($queueAlias, $timeout = 0)
    {
        $queues = is_array($queueAlias)
            ? $this->queueAliasResolver->getQueues($queueAlias)
            : $this->queueAliasResolver->getQueue($queueAlias);

        $payloadArray = [];
        $payloadSerialized = null;
        $givenQueue = null;
        $startAt = time();

        while ($payloadSerialized === null || $givenQueue === null) {
            foreach ($queues as $queue) {
                $jobs = $this->redis->zrangebyscore($queue, 0, time(), ['limit' => [0, 1]]);
                $payloadArray[$queue] = $jobs;

                if (count($jobs) > 0) {
                    $givenQueue = $queue;
                    $payloadSerialized = $jobs[0];
                    $this->redis->zRem($queue, $jobs[0]);

                    break;
                }
            }

            if ($timeout != 0 && $startAt + $timeout < time()) {
                break;
            }

            sleep(1);
        }

        if ($payloadSerialized === null || $givenQueue == null) {
            return [];
        }

        $payload         = $this->serializer->revert($payloadSerialized);
        $givenQueueAlias = $this->queueAliasResolver->getQueueAlias($givenQueue);

        /**
         * Dispatching consumer event...
         */
        $consumerEvent = new RSQueueConsumerEvent($payload, $payloadSerialized, $givenQueueAlias, $givenQueue, $this->redis);
        $this->eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_CONSUMER, $consumerEvent);

        return [$givenQueueAlias, $payload];
    }
}
