<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Command\Interfaces;

/**
 * Interface for all rs queue commands
 */
interface RSQueueCommandInterface
{

    /**
     * Definition method.
     *
     * All RSqueue commands must implements its own define() method
     * This method will subscribe command to desired queues
     * with their respective methods
     */
    public function define();
}
