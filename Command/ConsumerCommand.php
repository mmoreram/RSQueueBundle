<?php

/**
 * SommelierBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreramerino\RSQueueBundle\Command;

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
     * Queue name to consume from
     * 
     * @var string
     */
    protected $queueName;


    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->addOption(
                'timeout',
                null,
                InputOption::VALUE_OPTIONAL,
                'Timeout before change an empty queue to another random',
                2
            )
            ->addOption(
                'iterations',
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of iterations before this command kills itself',
                10
            )
            ->addOption(
                'sleep',
                null,
                InputOption::VALUE_OPTIONAL,
                'Timeout between each pop',
                0
            )
        ;
    }


    /**
     * Execute code.
     * 
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumer = $this->getContainer()->get('rsqueue.consumer');
        $iterations = (int) $input->getOption('iterations');
        $timeout = (int) $input->getOption('timeout');
        $sleep = (int) $input->getOption('sleep');
        $iterationsDone = 0;

        while ($payload = $consumer->retrieve($this->queueName, $timeout)) {

            $payload = unserialize($payload[1]);

            $this->consume($input, $output, $payload);

            $iterationsDone++;
            if (!is_null($iterations) && $iterationsDone >= $iterations) {

                exit;
            }

            sleep($sleep);
        }
    }


    /**
     * Consume method with retrieved queue value
     * 
     * @param InputInterface  $input   An InputInterface instance
     * @param OutputInterface $output  An OutputInterface instance
     * @param Mixed           $payload Data retrieved and unserialized from queue
     */
    abstract protected function consume(InputInterface $input, OutputInterface $output, $payload);
}