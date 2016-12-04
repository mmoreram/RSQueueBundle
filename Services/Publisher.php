<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Services;

use Mmoreram\RSQueueBundle\Event\RSQueuePublisherEvent;
use Mmoreram\RSQueueBundle\Exception\InvalidAliasException;
use Mmoreram\RSQueueBundle\RSQueueEvents;
use Mmoreram\RSQueueBundle\Services\Abstracts\AbstractService;

/**
 * Publisher class
 */
class Publisher extends AbstractService
{
    /**
     * Enqueues payload inside desired queue
     *
     * @param String $channelAlias Name of channel to publish payload
     * @param Mixed  $payload      Data to publish
     *
     * @return Publisher self Object
     *
     * @throws InvalidAliasException If any alias is not defined
     */
    public function publish($channelAlias, $payload)
    {
        $channel = $this->queueAliasResolver->getQueue($channelAlias);
        $payloadSerialized = $this->serializer->apply($payload);

        $this->redisAdapter->publish(
            $channel,
            $payloadSerialized
        );

        /**
         * Dispatching publisher event...
         */
        $publisherEvent = new RSQueuePublisherEvent($payload, $payloadSerialized, $channelAlias, $channel, $this->redisAdapter->getClient());
        $this->eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_PUBLISHER, $publisherEvent);

        return $this;
    }
}
