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
     * @var string
     *
     * Payload for testing
     */
    private $payload = '{"foo":"foodata","engonga":"someengongadata"}';


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

        $this->redis = $this->getMock('Predis\Client');
        $this->rsqueueProducerEvent = new RSQueueProducerEvent($this->payload, $this->queueAlias, $this->queueName, $this->redis);
    }


    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->rsqueueProducerEvent->getPayload(), $this->payload);
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