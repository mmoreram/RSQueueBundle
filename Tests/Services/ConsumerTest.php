<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Services;

use Mmoreram\RSQueueBundle\Services\Consumer;

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
        $timeout = 0;
        $payload = array('engonga');

        $redis = $this
            ->getMock('\Redis', array('blPop'));

        $redis
            ->expects($this->once())
            ->method('blPop')
            ->with($this->equalTo($queue), $this->equalTo($timeout))
            ->will($this->returnValue(array($queue, json_encode($payload))));

        $serializer = $this
            ->getMock('Mmoreram\RSQueueBundle\Serializer\JsonSerializer', array('revert'));

        $serializer
            ->expects($this->once())
            ->method('revert')
            ->with($this->equalTo(json_encode($payload)))
            ->will($this->returnValue($payload));

        $queueAliasResolver = $this
            ->getMockBuilder('Mmoreram\RSQueueBundle\Resolver\QueueAliasResolver')
            ->setMethods(array('getQueue', 'getQueueAlias'))
            ->disableOriginalConstructor()
            ->getMock();

        $queueAliasResolver
            ->expects($this->once())
            ->method('getQueue')
            ->with($this->equalTo($queueAlias))
            ->will($this->returnValue($queue));

        $queueAliasResolver
            ->expects($this->once())
            ->method('getQueueAlias')
            ->with($this->equalTo($queue))
            ->will($this->returnValue($queueAlias));

        $eventDispatcher = $this
            ->getMock('Symfony\Component\EventDispatcher\EventDispatcher', array('dispatch'));

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch');

        $consumer = new Consumer($eventDispatcher, $redis, $queueAliasResolver, $serializer);
        list($givenQueueAlias, $givenPayload) = $consumer->consume($queueAlias, $timeout);

        $this->assertEquals($queueAlias, $givenQueueAlias);
        $this->assertEquals($payload, $givenPayload);
    }
}
