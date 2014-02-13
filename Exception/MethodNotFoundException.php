<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Exception;

use Exception;

/**
 * Name is not a valid queue name Exception
 *
 * When queue name not belongs to any configured queue
 */
class MethodNotFoundException extends Exception
{
}
