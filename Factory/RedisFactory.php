<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Factory;

use Mmoreram\RSQueueBundle\Redis\AdapterInterface;
use Mmoreram\RSQueueBundle\Redis\PredisClientAdapter;
use Mmoreram\RSQueueBundle\Redis\RedisAdapter;
use Predis\Client;
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
     * Generate a AdapterInterface instance
     *
     * @return AdapterInterface
     */
    public function get()
    {
        if ($this->config['driver'] === 'predis') {

            $connectionParameters = array(
                'scheme' => 'tcp',
                'host' => $this->config['host'],
                'port' => $this->config['port'],
                'read_write_timeout' => -1
            );
            if ($this->config['database']) {
                $connectionParameters['database'] = $this->config['database'];
            }
            $redis = new Client($connectionParameters);

            return new PredisClientAdapter($redis);
        } else {
            $redis = new Redis;
            $redis->connect($this->config['host'], $this->config['port']);
            $redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
            if ($this->config['database']) {
                $redis->select($this->config['database']);
            }

            return new RedisAdapter($redis);
        }


    }
}
