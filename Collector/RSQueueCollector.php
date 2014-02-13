<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Collector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

use Mmoreram\RSQueueBundle\Event\RSQueueProducerEvent;
use Mmoreram\RSQueueBundle\Event\RSQueuePublisherEvent;

/**
 * Collector for RSQueue data
 *
 * All these methods are subscribed to custom RSQueueBundle events
 */
class RSQueueCollector extends DataCollector
{

    /**
     * Construct method for initializate all data
     *
     * Also initializes total value to 0
     */
    public function __construct()
    {
        $this->total = 0;
        $this->data = array(

            'prod'  =>  array(),
            'publ'  =>  array(),
            'total' =>  0,
        );
    }

    /**
     * Subscribed to RSQueueProducer event.
     *
     * Add to collect data a new producer action
     *
     * @param RSQueueProducerEvent $event Event fired
     *
     * @return QueueCollector self Object
     */
    public function onProducerAction(RSQueueProducerEvent $event)
    {
        $this->data['total']++;
        $this->data['prod'][] = array(
            'payload'   =>  $event->getPayloadSerialized(),
            'queue'     =>  $event->getQueueName(),
            'alias'     =>  $event->getQueueAlias(),
        );

        return $this;
    }

    /**
     * Subscribed to RSQueuePublisher event.
     *
     * Add to collect data a new publisher action
     *
     * @param RSQueuePublisherEvent $event Event fired
     *
     * @return QueueCollector self Object
     */
    public function onPublisherAction(RSQueuePublisherEvent $event)
    {
        $this->data['total']++;
        $this->data['publ'][] = array(
            'payload'   =>  $event->getPayloadSerialized(),
            'queue'     =>  $event->getChannelName(),
            'alias'     =>  $event->getChannelAlias(),
        );

        return $this;
    }

    /**
     * Get total of queue interactions
     *
     * @return integer
     */
    public function getTotal()
    {
        return (int) $this->data['total'];
    }

    /**
     * Get producer collection
     *
     * @return Array
     */
    public function getProducer()
    {
        return $this->data['prod'];
    }

    /**
     * Get publisher collection
     *
     * @return Array
     */
    public function getPublisher()
    {
        return $this->data['publ'];
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     *
     * @api
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {

    }

    /**
     * Return collector name
     *
     * @return string Collector name
     */
    public function getName()
    {
        return 'rsqueue_collector';
    }
}
