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
     * Queue names configured in config file
     */
    private $queueAliases;


    /**
     * @param Array $queueAliases Queue names array
     *
     * Construct method
     */
    public function __construct(Array $queueAliases)
    {
        $this->queueAliases = $queueAliases;
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
    public function get($queueAlias)
    {
        $this->check($queueAlias);

        return $this->queueAliases[$queueAlias];
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
    public function check($queueAlias)
    {
        if (!isset($this->queueAliases[$queueAlias])) {

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
        return array_search($queue, $this->queueAliases);
    }


    /**
     * Return aliases array
     *
     * @return array Aliases
     */
    public function getQueueAliases()
    {
        return $this->queueAliases;
    }
}