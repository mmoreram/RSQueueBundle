<?php

namespace Mmoreram\RSQueueBundle\Command;

use Mmoreram\RSQueueBundle\Command\Abstracts\AbstractExtendedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddRestartFlagCommand
 *
 * @package Mmoreram\RSQueueBundle\Command
 */
class AddRestartFlagCommand extends AbstractExtendedCommand
{
    const RSQUEUE_WORKERS_RESTART_TIMESTAMP= 'rsqueue_workers_restart_timestamp';

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;

        parent::__construct();
    }

    /**
     * @return int
     */
    protected function stopExecute()
    {
        return 0;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('rsqueue:add_restart_flag')
            ->setDescription('Add flag with timestamp for restart workers to redis.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void;
     */
    protected function executeCommand(InputInterface $input, OutputInterface $output)
    {
        $this->redis->set(self::RSQUEUE_WORKERS_RESTART_TIMESTAMP, time());

        return;
    }
}
