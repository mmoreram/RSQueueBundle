<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Event;

use Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent;

/**
 * Tests RSQueueConsumerEvent class
 */
class RSQueueConsumerEventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RSQueueConsumerEvent
     *
     * Object to test
     */
    private $rsqueueConsumerEvent;


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
        $this->rsqueueConsumerEvent = new RSQueueConsumerEvent($this->payload, $this->queueAlias, $this->queueName, $this->redis);
    }


    /**
     * Testing payload getter
     */
    public function testGetPayload()
    {
        $this->assertEquals($this->rsqueueConsumerEvent->getPayload(), $this->payload);
    }


    /**
     * Testing queuename getter
     */
    public function testGetQueueName()
    {
        $this->assertEquals($this->rsqueueConsumerEvent->getQueueName(), $this->queueName);
    }


    /**
     * Testing queuealias getter
     */
    public function testGetQueueAlias()
    {
        $this->assertEquals($this->rsqueueConsumerEvent->getQueueAlias(), $this->queueAlias);
    }


    /**
     * Testing Redis getter
     */
    public function testGetRedis()
    {
        $this->assertSame($this->rsqueueConsumerEvent->getRedis(), $this->redis);
    }
}