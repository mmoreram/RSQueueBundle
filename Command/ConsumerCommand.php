<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Command;

use Mmoreram\RSQueueBundle\Model\JobData;
use Mmoreram\RSQueueBundle\Services\Consumer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Exception\InvalidAliasException;
use Mmoreram\RSQueueBundle\Exception\MethodNotFoundException;
use Mmoreram\RSQueueBundle\Command\Abstracts\AbstractRSQueueCommand;

/**
 * Abstract consumer command
 *
 * Events :
 *
 *     Each time a consumer recieves a new element, this throws a new
 *     rsqueue.consumer Event
 *
 * Exceptions :
 *
 *     If any of inserted queues or channels is not defined in config file
 *     as an alias, a new InvalidAliasException will be thrown
 *
 *     Likewise, if any ot inserted associated methods does not exist or is not
 *     callable, a new MethodNotFoundException will be thrown
 */
abstract class ConsumerCommand extends AbstractRSQueueCommand
{
    /**
     * Use this variable to stop consumption
     *
     * @var bool
     */
    protected $breakExecute = false;

    /**
     * Adds a queue to subscribe on
     *
     * Checks if queue assigned method exists and is callable
     *
     * @param String $queueAlias  Queue alias
     * @param String $queueMethod Queue method
     *
     * @return SubscriberCommand self Object
     *
     * @throws MethodNotFoundException If any method is not callable
     */
    protected function addQueue($queueAlias, $queueMethod)
    {
        return $this->addMethod($queueAlias, $queueMethod);
    }

    /**
     * Configure command
     *
     * Some options are included
     * * timeout ( default: 0)
     * * iterations ( default: 0)
     * * sleep ( default: 0)
     *
     * Important !!
     *
     * All Commands with this consumer behaviour must call parent() configure method
     */
    protected function configure()
    {
        $this
            ->addOption(
                'timeout',
                null,
                InputOption::VALUE_OPTIONAL,
                'Consumer timeout.
                If 0, no timeout is set.
                Otherwise consumer will lose conection after timeout if queue is empty.
                By default, 1',
                1
            )
            ->addOption(
                'iterations',
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of iterations before this command kills itself.
                If 0, consumer will listen queue until process is killed by hand or by exception.
                You can manage this behavour by using some Process Control System, e.g. Supervisord
                By default, 0',
                0
            )
            ->addOption(
                'sleep',
                null,
                InputOption::VALUE_OPTIONAL,
                'Timeout between each iteration ( in seconds ).
                If 0, no time will be waitted between them.
                Otherwise, php will sleep X seconds each iteration.
                By default, 0',
                0
            )
            ->addOption(
                'workTime',
                null,
                InputOption::VALUE_OPTIONAL,
                'Time in seconds after witch command kills itself.
                If 0, workTime is disabled.',
                0
            )
        ;
    }

    /**
     * Execute code.
     *
     * Each time new payload is consumed from queue, consume() method is called.
     * When iterations get the limit, process literaly dies
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @throws InvalidAliasException If any alias is not defined
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->define();

        pcntl_signal(SIGTERM, [$this, 'stopExecute']);
        pcntl_signal(SIGINT, [$this, 'stopExecute']);

        /** @var Consumer $consumer */
        $consumer = $this->getContainer()->get('rsqueue.consumer');
        $iterations = (int) $input->getOption('iterations');
        $timeout = (int) $input->getOption('timeout');
        $workTime = (int) $input->getOption('workTime');
        $sleep = (int) $input->getOption('sleep');
        $iterationsDone = 0;
        $queuesAlias = array_keys($this->methods);
        $now = time();

        if ($this->shuffleQueues()) {
            shuffle($queuesAlias);
        }

        while (true) {
            $job = $consumer->consume($queuesAlias, $timeout);

            if ($job instanceof JobData) {
                $method = $this->methods[$job->getQueue()];

                /**
                 * All custom methods must have these parameters
                 *
                 * InputInterface  $input   An InputInterface instance
                 * OutputInterface $output  An OutputInterface instance
                 * Mixed           $payload Payload
                 */
                $this->$method($input, $output, $job->getPayload());
            }

            if ( ($iterations > 0) && (++$iterationsDone >= $iterations) ) {
                break;
            }

            if ($workTime > 0 && $now + $workTime <= time()) {
                break;
            }

            pcntl_signal_dispatch();

            if ($this->breakExecute) {
                break;
            }

            sleep($sleep);
        }
    }

    protected function stopExecute()
    {
        $this->breakExecute = true;
    }
}
