<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Event;

use Mmoreram\RSQueueBundle\Event\RSQueueProducerEvent;

/**
 * Tests RSQueueProducerEvent class
 */
class RSQueueProducerEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RSQueueProducerEvent
     *
     * Object to test
     */
    private $rsqueueProducerEvent;

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
     * Queue Alias
     */
    private $queueAlias = 'queueAlias';

    /**
     * @var string
     *
     * Queue Name
     */
    private $queueName = 'queueName';

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
        $this->rsqueueProducerEvent = new RSQueueProducerEvent($this->payload, $this->payloadSerialized, $this->queueAlias, $this->queueName, $this->redis);
    }

    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->rsqueueProducerEvent->getPayload(), $this->payload);
    }

    /**
     * Testing payload serialized getter
     */
    public function testGetPayloadSerialized()
    {
        $this->assertEquals($this->rsqueueProducerEvent->getPayloadSerialized(), $this->payloadSerialized);
    }

    /**
     * Testing queuename getter
     */
    public function testGetQueueName()
    {
        $this->assertEquals($this->rsqueueProducerEvent->getQueueName(), $this->queueName);
    }

    /**
     * Testing queuealias getter
     */
    public function testGetQueueAlias()
    {
        $this->assertEquals($this->rsqueueProducerEvent->getQueueAlias(), $this->queueAlias);
    }

    /**
     * Testing Redis getter
     */
    public function testGetRedis()
    {
        $this->assertSame($this->rsqueueProducerEvent->getRedis(), $this->redis);
    }
}
