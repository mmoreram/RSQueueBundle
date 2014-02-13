<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Command\Abstracts\AbstractRSQueueCommand;

/**
 * Abstract PSubscriber command
 *
 * Events :
 *
 * Events :
 *
 *     Each time a psubscriber recieves a new element, this throws a new
 *     rsqueue.psubscriber Event
 *
 * Exceptions :
 *
 *     If any ot inserted associated methods does not exist or is not
 *     callable, a new MethodNotFoundException will be thrown
 */
abstract class PSubscriberCommand extends AbstractRSQueueCommand
{

    /**
     * Adds a pattern to subscribe on
     *
     * Checks if channel assigned method exists and is callable
     *
     * @param String $pattern       Pattern
     * @param String $patternMethod Pattern method
     *
     * @return SubscriberCommand self Object
     *
     * @throws MethodNotFoundException If any method is not callable
     */
    protected function addPattern($pattern, $patternMethod)
    {
        return $this->addMethod($pattern, $patternMethod);
    }

    /**
     * Execute code.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->define();

        $serializer = $this->getContainer()->get('rs_queue.serializer');
        $psubscriberCommand = $this;
        $methods = $this->methods;

        $patterns = array_keys($methods);

        if ($this->shuffleQueues()) {

            shuffle($patterns);
        }

        $this
            ->getContainer()
            ->get('rs_queue.redis')
            ->psubscribe($patterns, function ($redis, $pattern, $channel, $payloadSerialized) use ($methods, $psubscriberCommand, $serializer, $input, $output) {

                $payload = $serializer->revert($payloadSerialized);
                $method = $methods[$pattern];

                /**
                 * All custom methods must have these parameters
                 *
                 * InputInterface  $input   An InputInterface instance
                 * OutputInterface $output  An OutputInterface instance
                 * Mixed           $payload Payload
                 */
                $psubscriberCommand->$method($input, $output, $payload);
            });
    }
}
