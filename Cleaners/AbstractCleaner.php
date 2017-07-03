<?php

namespace Mmoreram\RSQueueBundle\Cleaners;

/**
 * Class AbstractCleaner
 *
 * @package Mmoreram\RSQueueBundle\Cleaners
 */
abstract class AbstractCleaner
{
    /**
     * @return mixed
     */
    abstract public function cacheClear();

    /**
     * @return mixed
     */
    abstract public function getManagerClassPath();

    /**
     * @param $className
     *
     * @return bool
     */
    public function ifClassExist($className)
    {
       return class_exists($className);
    }
}
