<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Bundle\SommelierBundle\Exception;

use Exception;

/**
 * Name is not a valid queue name Exception
 * 
 * When queue name not belongs to any configured queue
 */
class InvalidQueueNameException extends Exception
{
}