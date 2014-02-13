<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Event\RSQueueSubscriberEvent;
use Mmoreram\RSQueueBundle\Command\Abstracts\AbstractRSQueueCommand;
use Mmoreram\RSQueueBundle\RSQueueEvents;

/**
 * Abstract Subscriber command
 *
 * Events :
 *
 *     Each time a subscriber recieves a new element, this throws a new
 *     rsqueue.subscriber Event
 *
 * Exceptions :
 *
 *     If any of inserted queues or channels is not defined in config file
 *     as an alias, a new InvalidAliasException will be thrown
 *
 *     Likewise, if any ot inserted associated methods does not exist or is not
 *     callable, a new MethodNotFoundException will be thrown
 */
abstract class SubscriberCommand extends AbstractRSQueueCommand
{
    /**
     * Adds a queue to subscribe on
     *
     * Checks if queue assigned method exists and is callable
     *
     * @param String $channelAlias  Queue alias
     * @param String $channelMethod Queue method
     *
     * @return SubscriberCommand self Object
     *
     * @throws MethodNotFoundException If any method is not callable
     */
    protected function addChannel($channelAlias, $channelMethod)
    {
        return $this->addMethod($channelAlias, $channelMethod);
    }

    /**
     * Execute code.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @throws InvalidAliasException If any alias is not defined
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * Define all channels this command must listen to
         */
        $this->define();

        $serializer = $this->getContainer()->get('rs_queue.serializer');
        $resolver = $this->getContainer()->get('rs_queue.resolver.queuealias');
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $methods = $this->methods;

        $channelAliases = array_keys($methods);
        $channels = $resolver->getQueues($channelAliases);

        if ($this->shuffleQueues()) {

            shuffle($channels);
        }

        $this
            ->getContainer()
            ->get('rs_queue.redis')
            ->subscribe($channels, function ($redis, $channel, $payloadSerialized) use ($methods, $resolver, $serializer, $eventDispatcher, $input, $output) {

                $channelAlias = $resolver->getQueueAlias($channel);
                $method = $methods[$channelAlias];
                $payload = $serializer->revert($payloadSerialized);

                /**
                 * Dispatching subscriber event...
                 */
                $subscriberEvent = new RSQueueSubscriberEvent($payload, $payloadSerialized, $channelAlias, $channel, $redis);
                $eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_SUBSCRIBER, $subscriberEvent);

                /**
                 * All custom methods must have these parameters
                 *
                 * InputInterface  $input   An InputInterface instance
                 * OutputInterface $output  An OutputInterface instance
                 * Mixed           $payload Payload
                 */
                $this->$method($input, $output, $payload);
            });
    }
}
