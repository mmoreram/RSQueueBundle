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
 * PSubscriber abstract command
 */
abstract class PSubscriberCommand extends ContainerAwareCommand
{

    /**
     * @var array
     *
     * Array of channel patterns with their methods
     */
    private $patterns;


    /**
     * Adds a pattern to subscribe on
     *
     * Checks if channel is defined in config
     * Checks if channel assigned method exists and is callable
     *
     * @param String $pattern       Pattern
     * @param String $patternAlias  Pattern alias
     * @param String $patternMethod Pattern method
     *
     * @return SubscriberCommand self Object
     *
     * @throws MethodNotFoundException If any method is not callable
     */
    protected function addPattern($pattern, $patternAlias, $patternMethod)
    {
        if (!is_callable(array($this, $patternMethod))) {

            throw new methodNotExistsException($patternAlias);
        }

        $this->patterns[$pattern] = array(
            'alias'     =>  $patternAlias,
            'method'    =>  $patternMethod,
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

        $patterns = $this->patterns;
        $serializer = $this->getContainer()->get('rs_queue.serializer');

        $this
            ->getContainer()
            ->get('snc_redis.default')
            ->psubscribe($this->patterns, function($redis, $pattern, $channel, $payloadSerialized) use ($patterns, $serializer) {

                $patternAlias = $patterns[$pattern]['alias'];
                $payload = $serializer->revert($payloadSerialized);

                /**
                 * Dispatching subscriber event...
                 */
                $pSubscriberEvent = new RSQueueSubscriberEvent($payload, $payloadSerialized, $patternAlias, $channel, $redis);
                $this->eventDispatcher->dispatch(RSQueueEvents::RSQUEUE_PSUBSCRIBER, $pSubscriberEvent);

                $method = $patterns[$pattern]['method'];

                /**
                 * All custom methods must have these parameters
                 *
                 * Mixed  $payload      Payload
                 * String $patternAlias Pattern alias
                 * String $channel      Channel name
                 * Redis  $redis        Redis instance
                 */
                $this->$method($payload, $patternAlias, $channel, $redis);
            });
    }


    /**
     * Configure before Execute
     */
    abstract protected function preExecute();
}