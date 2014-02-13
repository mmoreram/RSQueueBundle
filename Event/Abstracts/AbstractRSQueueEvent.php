<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Event\Abstracts;

/**
 * Abstract queue event
 */
abstract class AbstractRSQueueEvent extends AbstractRSEvent
{

    /**
     * @var String
     *
     * Queue alias
     */
    protected $queueAlias;

    /**
     * @var String
     *
     * Real queue name
     */
    protected $queueName;

    /**
     * Construct method
     *
     * @param Mixed  $payload           Payload
     * @param String $payloadSerialized Payload serialized
     * @param string $queueAlias        Queue alias
     * @param string $queueName         Queue name
     * @param Redis  $redis             Redis instance
     */
    public function __construct($payload, $payloadSerialized, $queueAlias, $queueName, $redis)
    {
        parent::__construct($payload, $payloadSerialized, $redis);

        $this->queueAlias = $queueAlias;
        $this->queueName = $queueName;
    }

    /**
     * Return queue alias
     *
     * @return string Queue alias
     */
    public function getQueueAlias()
    {
        return $this->queueAlias;
    }

    /**
     * Return queue name
     *
     * @return string Queue name
     */
    public function getQueueName()
    {
        return $this->queueName;
    }
}
