<?php

namespace Mmoreram\RSQueueBundle\Tests\Resolver;

use Doctrine\Common\Persistence\ObjectManager;
use Mmoreram\RSQueueBundle\Listeners\DoctrineCacheClear;
use Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent;
use Symfony\Bridge\Doctrine\ManagerRegistry;

/**
 * Class RSQueueConsumerListenerTest
 *
 * @package Mmoreram\RSQueueBundle\Tests\Resolver
 */
class RSQueueConsumerListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testConsumerListener()
    {
        $objectManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager
            ->expects($this->once())
            ->method('clear');

        $managerRegistry = $this
            ->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $managerRegistry
            ->expects($this->once())
            ->method('getManager')
            ->willReturn($objectManager);

        $listener = new DoctrineCacheClear($managerRegistry);

        $event = $this->getMockBuilder(RSQueueConsumerEvent::class)->disableOriginalConstructor()->getMock();

        $listener->checkRSQConsumerEvent($event);
    }
}
