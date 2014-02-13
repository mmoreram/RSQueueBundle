<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle;

/**
 * Events dispatched by RSQueueBundle
 */
class RSQueueEvents
{
    /**
     * The rsqueue.consumer is thrown each time a job is consumed by consumer
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent instance
     *
     * @var string
     */
    const RSQUEUE_CONSUMER = 'rsqueue.consumer';

    /**
     * The rsqueue.subscriber is thrown each time a job is consumed by subscriber
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueueSubscriberEvent instance
     *
     * @var string
     */
    const RSQUEUE_SUBSCRIBER = 'rsqueue.subscriber';

    /**
     * The rsqueue.producer is thrown each time a job is consumed by producer
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueueProducerEvent instance
     *
     * @var string
     */
    const RSQUEUE_PRODUCER = 'rsqueue.producer';

    /**
     * The rsqueue.publisher is thrown each time a job is consumed by publisher
     *
     * The event listener recieves an
     * Mmoreram\RSQueueBundle\Event\RSQueuePublisherEvent instance
     *
     * @var string
     */
    const RSQUEUE_PUBLISHER = 'rsqueue.publisher';
}
