<?php

namespace Mmoreram\RSQueueBundle\Model;

/**
 * Class JobData
 *
 * @package Mmoreram\RSQueueBundle\Model
 */
class JobData extends AbstractJobData
{
    /**
     * @var string
     */
    protected $queue;

    /**
     * @var mixed
     */
    protected $payload;

    /**
     * JobData constructor.
     *
     * @param string $queue
     * @param mixed  $payload
     */
    public function __construct($queue, $payload)
    {
        $this->queue   = $queue;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param string $queue
     *
     * @return $this
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     *
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }
}
