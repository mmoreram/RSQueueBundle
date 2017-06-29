<?php

namespace Mmoreram\RSQueueBundle\Listeners;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Mmoreram\RSQueueBundle\Event\RSQueueConsumerEvent;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ConsumerListener
 *
 * @package Mmoreram\RSQueueBundle\Listeners
 */
class ConsumerListener
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * ConsumerListener constructor.
     *
     * @param $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param RSQueueConsumerEvent $event
     */
    public function checkRSQConsumerEvent(RSQueueConsumerEvent $event)
    {
        $this->registry->getManager()->clear();
    }
}
