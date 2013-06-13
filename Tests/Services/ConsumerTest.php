<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Services;

use Mmoreram\RSQueueBundle\Services\Consumer;
use Mmoreram\RSQueueBundle\RSQueueEvents;

/**
 * Tests Consumer class
 */
class ConsumerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests consume method
     */
    public function testConsume()
    {
        $queueAlias = 'alias';
        $queue = 'queue';
        $timeout = 'timeout';
        $payload = array('engonga');

        $redis = $this 
            ->getMock('Predis\Client', array('blpop'));

        $redis
            ->expects($this->once())
            ->method('blpop')
            ->with($this->equalTo($queue), $this->equalTo($timeout))
            ->will($this->returnValue(array('queue', json_encode($payload))));

        $serializer = $this
            ->getMock('Mmoreram\RSQueueBundle\Serializer\JsonSerializer', array('revert'));

        $serializer
            ->expects($this->once())
            ->method('revert')
            ->with($this->equalTo(json_encode($payload)))
            ->will($this->returnValue($payload));

        $RSQueueConsumerEvent = $this   
            ->getMockBuilder('Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $queueAliasResolver = $this 
            ->getMockBuilder('Mmoreram\RSQueueBundle\Resolver\QueueAliasResolver')
            ->setMethods(array('get'))
            ->disableOriginalConstructor()
            ->getMock();

        $queueAliasResolver
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($queueAlias))
            ->will($this->returnValue($queue));

        $eventDispatcher = $this
            ->getMock('Symfony\Component\EventDispatcher\EventDispatcher', array('dispatch'));

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch');

        $consumer = new Consumer($eventDispatcher, $redis, $queueAliasResolver, $serializer);
        $payloadReturned = $consumer->consume($queueAlias, $timeout);

        $this->assertEquals($payload, $payloadReturned);
    }
}