<?php

/**
 * RSChannelBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Event;

use Mmoreram\RSQueueBundle\Event\RSQueuePSubscriberEvent;

/**
 * Tests RSChannelPSubscriberEvent class
 */
class RSQueuePSubscriberEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RSChannelPSubscriberEvent
     *
     * Object to test
     */
    private $rsqueuePSubscriberEvent;


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
        $this->rsqueuePSubscriberEvent = new RSQueuePSubscriberEvent($this->payload, $this->channelAlias, $this->channelName, $this->redis);
    }


    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->rsqueuePSubscriberEvent->getPayload(), $this->payload);
    }


    /**
     * Testing channelname getter
     */
    public function testGetChannelName()
    {
        $this->assertEquals($this->rsqueuePSubscriberEvent->getChannelName(), $this->channelName);
    }


    /**
     * Testing channelalias getter
     */
    public function testGetChannelAlias()
    {
        $this->assertEquals($this->rsqueuePSubscriberEvent->getChannelAlias(), $this->channelAlias);
    }


    /**
     * Testing Redis getter
     */
    public function testGetRedis()
    {
        $this->assertSame($this->rsqueuePSubscriberEvent->getRedis(), $this->redis);
    }
}