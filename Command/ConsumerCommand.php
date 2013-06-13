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


/**
 * Command for executing a parser
 */
abstract class ConsumerCommand extends ContainerAwareCommand
{

    /**
     * @var string
     * 
     * Queue name to consume from
     */
    private $queueAlias;


    /**
     * Set queue alias to consume from
     * 
     * @param String $queueAlias Queue alias
     * 
     * @return ConsumerCommand self Object
     * 
     * @throws InvalidAliasException If any alias is not defined
     */
    protected function setQueueAlias($queueAlias)
    {
        $this
            ->getContainer()
            ->get('rsqueue.resolver.queuealias')
            ->check($queueAlias);

        $this->queueAlias = $queueAlias;

        return $this;
    }


    /**
     * Configure command
     * 
     * Some options are included
     * * timeout ( default: 0)
     * * iterations ( default: 0)
     * * sleep ( default: 0)
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
                By default, 0',
                0
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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->preExecute();

        $consumer = $this->getContainer()->get('rsqueue.consumer');
        $iterations = (int) $input->getOption('iterations');
        $timeout = (int) $input->getOption('timeout');
        $sleep = (int) $input->getOption('sleep');
        $iterationsDone = 0;

        while ($payload = $consumer->consume($this->queueAlias, $timeout)) {

            $this->consume($input, $output, $payload);

            if ( ($iterations > 0) && (++$iterationsDone >= $iterations) ) {

                exit;
            }

            sleep($sleep);
        }
    }


    /**
     * Configure before Execute
     */
    abstract protected function preExecute();


    /**
     * Consume method with retrieved queue value
     * 
     * @param InputInterface  $input   An InputInterface instance
     * @param OutputInterface $output  An OutputInterface instance
     * @param Mixed           $payload Data retrieved and unserialized from queue
     */
    abstract protected function consume(InputInterface $input, OutputInterface $output, $payload);
}