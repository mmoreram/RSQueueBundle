<?php

/**
 * RSChannelBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Event;

use Mmoreram\RSQueueBundle\Event\RSQueuePublisherEvent;

/**
 * Tests RSChannelPublisherEvent class
 */
class RSQueuePublisherEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RSChannelPublisherEvent
     * 
     * Object to test
     */
    private $RSQueuePublisherEvent;


    /**
     * @var string
     * 
     * Payload for testing
     */
    private $payload = '{"foo":"foodata","engonga":"someengongadata"}';


    /**
     * @var string
     * 
     * Channel Alias
     */
    private $channelAlias = 'channelAlias';


    /**
     * @var string
     * 
     * Channel Name
     */
    private $channelName = 'channelName';


    /**
     * @var Redis
     * 
     * Redis mock instance
     */
    private $redis;


    /**
     * Setup
     */
    public function setUp()
    {

        $this->redis = $this->getMock('Predis\Client');
        $this->RSQueuePublisherEvent = new RSQueuePublisherEvent($this->payload, $this->channelAlias, $this->channelName, $this->redis);
    }


    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->RSQueuePublisherEvent->getPayload(), $this->payload);
    }


    /**
     * Testing channelname getter
     */
    public function testGetChannelName()
    {
        $this->assertEquals($this->RSQueuePublisherEvent->getChannelName(), $this->channelName);
    }


    /**
     * Testing channelalias getter
     */
    public function testGetChannelAlias()
    {
        $this->assertEquals($this->RSQueuePublisherEvent->getChannelAlias(), $this->channelAlias);
    }


    /**
     * Testing Redis getter
     */
    public function testGetRedis()
    {
        $this->assertSame($this->RSQueuePublisherEvent->getRedis(), $this->redis);
    }
}