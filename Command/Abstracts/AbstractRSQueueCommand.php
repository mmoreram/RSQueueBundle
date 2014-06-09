<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Command\Abstracts;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Mmoreram\RSQueueBundle\Command\Interfaces\RSQueueCommandInterface;
use Mmoreram\RSQueueBundle\Exceptions\MethodNotFoundException;

/**
 * Abstract rs queue command
 */
abstract class AbstractRSQueueCommand extends ContainerAwareCommand implements RSQueueCommandInterface
{

    /**
     * @var array
     *
     * Array with all configured queues/ with their callable methods
     */
    protected $methods = array();

    /**
     * Adds a queue/channel to subscribe on
     *
     * Checks if queue assigned method exists and is callable
     *
     * @param String $alias  Queue alias
     * @param String $method Queue method
     *
     * @return SubscriberCommand self Object
     *
     * @throws MethodNotFoundException If any method is not callable
     */
    protected function addMethod($alias, $method)
    {
        if (!is_callable(array($this, $method))) {

            throw new MethodNotFoundException($alias);
        }

        $this->methods[$alias] = $method;

        return $this;
    }

    /**
     * Set automatic queue mixing when several queues are defined.
     *
     * This method returns if queue order must be shuffled before processing them
     *
     * By default is false, so same order will be passed as defined.
     *
     * @return boolean Shuffle before passing to Gearman
     */
    protected function shuffleQueues()
    {
        return false;
    }
}
