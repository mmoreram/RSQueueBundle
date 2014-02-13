<?php

/**
 * RSChannelBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Event;

use Mmoreram\RSQueueBundle\Event\RSQueueSubscriberEvent;

/**
 * Tests RSChannelSubscriberEvent class
 */
class RSQueueSubscriberEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RSChannelSubscriberEvent
     *
     * Object to test
     */
    private $rsqueueSubscriberEvent;

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
        $this->rsqueueSubscriberEvent = new RSQueueSubscriberEvent($this->payload, $this->payloadSerialized, $this->channelAlias, $this->channelName, $this->redis);
    }

    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->rsqueueSubscriberEvent->getPayload(), $this->payload);
    }

    /**
     * Testing payload serialized getter
     */
    public function testGetPayloadSerialized()
    {
        $this->assertEquals($this->rsqueueSubscriberEvent->getPayloadSerialized(), $this->payloadSerialized);
    }

    /**
     * Testing channelname getter
     */
    public function testGetChannelName()
    {
        $this->assertEquals($this->rsqueueSubscriberEvent->getChannelName(), $this->channelName);
    }

    /**
     * Testing channelalias getter
     */
    public function testGetChannelAlias()
    {
        $this->assertEquals($this->rsqueueSubscriberEvent->getChannelAlias(), $this->channelAlias);
    }

    /**
     * Testing Redis getter
     */
    public function testGetRedis()
    {
        $this->assertSame($this->rsqueueSubscriberEvent->getRedis(), $this->redis);
    }
}
