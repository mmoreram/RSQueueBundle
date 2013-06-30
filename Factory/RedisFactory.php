<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Factory;

use Redis;

/**
 * Interface for any kind of serialization
 */
class RedisFactory
{
    /**
     * Generate new Predis instance
     *
     * @return \Redis instance
     */
    public function get()
    {
        $redis = new Redis;
        $redis->connect('127.0.0.1', 6379);
        $redis->setOption(Redis::OPT_READ_TIMEOUT, -1);

        return $redis;
    }
}