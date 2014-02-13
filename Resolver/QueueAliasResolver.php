<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Resolver;

use Mmoreram\RSQueueBundle\Exception\InvalidAliasException;

/**
 * Abstract service
 *
 * Provides base structure of all rsqueue services
 */
class QueueAliasResolver
{
    /**
     * @var Array
     *
     * Queue names. Key is alias, value is queue real name
     *
     * This value is set in bundle config file
     */
    private $queues;

    /**
     * @var Array
     *
     * Queue aliases. Key is queue real name, value is alias
     */
    private $queueAliases;

    /**
     * Construct method
     *
     * @param Array $queues Queue names array
     */
    public function __construct(Array $queues)
    {
        $this->queues = $queues;
        $this->queueAliases = array_flip($queues);
    }

    /**
     * Given an array of queueAliases, return a valid queueNames array
     *
     * @param Array $queueAlias Queue alias array
     *
     * @return Array valid queueName array
     *
     * @throws InvalidAliasException If any queueAlias is not defined
     */
    public function getQueues(Array $queueAlias)
    {
        $queues = array();
        foreach ($queueAlias as $alias) {

            $queues[] = $this->getQueue($alias);
        }

        return $queues;
    }

    /**
     * Return real queue name by defined QueueAlias
     *
     * @param string $queueAlias Queue alias
     *
     * @return string real queue name
     *
     * @throws InvalidAliasException If queueAlias is not defined
     */
    public function getQueue($queueAlias)
    {
        $this->checkQueue($queueAlias);

        return $this->queues[$queueAlias];
    }

    /**
     * Check if given queue alias can be resolved
     *
     * @param string $queueAlias Queue alias
     *
     * @return boolean queue alias can be resolved
     *
     * @throws InvalidAliasException If queueAlias is not defined
     */
    public function checkQueue($queueAlias)
    {
        if (!isset($this->queues[$queueAlias])) {

            throw new InvalidAliasException;
        }

        return true;
    }

    /**
     * Get alias given queue name
     *
     * @param string $queue Queue name
     *
     * @return string Queue alias if exists
     */
    public function getQueueAlias($queue)
    {
        return $this->queueAliases[$queue];
    }
}
