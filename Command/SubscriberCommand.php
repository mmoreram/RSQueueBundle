<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Event\RSQueueSubscriberEvent;


/**
 * Command for executing a parser
 */
abstract class SubscriberCommand extends ContainerAwareCommand
{

    /**
     * @var array
     * 
     * Array of queue aliases with their methods
     */
    private $channels;


    /**
     * Adds a queue to subscribe on
     * 
     * Checks if queue is defined in config
     * Checks if queue assigned method exists and is callable
     * 
     * @param String $channelAlias  Queue alias
     * @param String $channelMethod Queue method
     * 
     * @return SubscriberCommand self Object
     * 
     * @throws InvalidAliasException   If any alias is not defined
     * @throws MethodNotFoundException If any method is not callable
     */
    protected function addQueueAlias($channelAlias, $channelMethod)
    {
        $channelName = $container->get('rsqueue.resolver.queuealias')->get($channelAlias);

        if (!is_callable(array($this, $channelMethod))) {

            throw new MethodNotFoundException($channelAlias);
        }

        $this->channels[$channelName] = array(
            'alias'     =>  $channelAlias,
            'method'    =>  $channelMethod,
        );

        return $this;
    }


    /**
     * Execute code.
     * 
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->preExecute();
        
        $channels = $this->channels;

        $this   
            ->getContainer()
            ->get('snc_redis.default')
            ->subscribe($channelsNames, function($redis, $channel, $payload) use ($channels) {

                $channelAlias = $channels[$channel]['alias'];
                $channelMethod = $channels[$channel]['method'];

                /**
                 * Dispatching subscriber event...
                 */
                $subscriberEvent = new RSQueueSubscriberEvent($payload, $channelAlias, $channel, $redis);
                $this->eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_SUBSCRIBER, $subscriberEvent);

                /**
                 * All custom methods must have these parameters
                 * 
                 * Mixed  $payload    Payload
                 * String $channelAlias Queue alias
                 * String $channel      Queue name
                 * Redis  $redis      Redis instance
                 */
                $this->$method($payload, $channelAlias, $channel, $redis);
            });
    }


    /**
     * Configure before Execute
     */
    abstract protected function preExecute();
}