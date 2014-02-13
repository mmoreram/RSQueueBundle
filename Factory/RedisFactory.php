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
     * @var array
     *
     * Settings for connection to redis.
     *
     * This value is set in bundle config file
     */
    public $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Generate new Predis instance
     *
     * @return \Redis instance
     */
    public function get()
    {
        $redis = new Redis;
        $redis->connect($this->config['host'], $this->config['port']);
        $redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
        if ($this->config['database']) {
            $redis->select($this->config['database']);
        }

        return $redis;
    }
}
