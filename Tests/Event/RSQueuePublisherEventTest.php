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
    private $rsqueuePublisherEvent;

    /**
     * @var array
     *
     * Payload for testing
     */
    private $payload = array(
        'foo'   =>  'foodata',
        'engonga'   =>  'someengongadata'
    );

    /**
     * @var string
     *
     * Payload serialized
     */
    private $payloadSerialized = '{"foo":"foodata","engonga":"someengongadata"}';

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

        $this->redis = $this->getMock('\Redis');
        $this->rsqueuePublisherEvent = new RSQueuePublisherEvent($this->payload, $this->payloadSerialized, $this->channelAlias, $this->channelName, $this->redis);
    }

    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->rsqueuePublisherEvent->getPayload(), $this->payload);
    }

    /**
     * Testing payload serialized getter
     */
    public function testGetPayloadSerialized()
    {
        $this->assertEquals($this->rsqueuePublisherEvent->getPayloadSerialized(), $this->payloadSerialized);
    }

    /**
     * Testing channelname getter
     */
    public function testGetChannelName()
    {
        $this->assertEquals($this->rsqueuePublisherEvent->getChannelName(), $this->channelName);
    }

    /**
     * Testing channelalias getter
     */
    public function testGetChannelAlias()
    {
        $this->assertEquals($this->rsqueuePublisherEvent->getChannelAlias(), $this->channelAlias);
    }

    /**
     * Testing Redis getter
     */
    public function testGetRedis()
    {
        $this->assertSame($this->rsqueuePublisherEvent->getRedis(), $this->redis);
    }
}
