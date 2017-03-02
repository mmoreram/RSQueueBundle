<?php

namespace Mmoreram\RSQueueBundle\Command\Abstracts;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractLockedCommand
 *
 * @package Mmoreram\RSQueueBundle\Command\Abstracts
 */
abstract class AbstractLockedCommand extends ContainerAwareCommand
{
    /**
     * Important to always call parent::configure!!!!!
     */
    protected function configure()
    {
        $this
            ->addOption(
                'lockFile',
                null,
                InputOption::VALUE_OPTIONAL,
                'Lock file.'
            )
            -> addOption(
                'gracefulShutdown',
                false,
                InputOption::VALUE_OPTIONAL,
                'Force graceful shutdown of command.'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int | null
     */
    final protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lockHandler = $this->getContainer()->get('rs_queue.lock_handler');
        $lockFile = $input->getOption('lockFile');
        $graceful = $input->getOption('gracefulShutdown');

        if (!is_null($lockFile)) {
            if (!$lockHandler->lock($lockFile)) {
                return 0;
            }
        }

        if ($graceful) {
            pcntl_signal(SIGTERM, [$this, 'stopExecute']);
            pcntl_signal(SIGINT, [$this, 'stopExecute']);
        }

        try {
            $this->executeCommand($input, $output);
        } finally {
            if (!is_null($lockFile)) {
                $lockHandler->unlock($lockFile);
            }
        }

        return 0;
    }

    /**
     * Definition of what will happen when you kill the command
     *
     * @return void
     */
    abstract protected function stopExecute();

    /**
     * Definition of the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int | null
     */
    abstract protected function executeCommand(InputInterface $input, OutputInterface $output);
}
