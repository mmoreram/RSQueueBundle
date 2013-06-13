<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Event\Abstracts;

use Mmoreram\RSQueueBundle\Event\Abstracts\AbstractRSEvent;


abstract class AbstractRSChannelEvent extends AbstractRSEvent
{

    /**
     * @var String
     * 
     * Channel alias
     */
    protected $channelAlias;


    /**
     * @var String
     * 
     * Real channel name
     */
    protected $channelName;


    /**
     * Construct method
     * 
     * @param Mixed  $payload      Payload
     * @param string $channelAlias Channel alias
     * @param string $channelName  Channel name
     * @param Redis  $redis        Redis instance
     */
    public function __construct($payload, $channelAlias, $channelName, $redis)
    {
        parent::__construct($payload, $redis);

        $this->channelAlias = $channelAlias;
        $this->channelName = $channelName;
    }


    /**
     * Return channel alias
     * 
     * @return string Channel alias
     */
    public function getChannelAlias()
    {
        return $this->channelAlias;
    }


    /**
     * Return channel name
     * 
     * @return string Channel name
     */
    public function getChannelName()
    {
        return $this->channelName;
    }
}
