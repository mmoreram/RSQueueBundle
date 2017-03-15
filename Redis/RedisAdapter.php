<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 04/12/16
 * Time: 12:14
 */

namespace Mmoreram\RSQueueBundle\Redis;


/**
 * Class RedisAdapter
 * @package Mmoreram\RSQueueBundle\Redis
 */
class RedisAdapter implements AdapterInterface
{
    /**
     * @var \Redis
     */
    protected $client;

    /**
     * RedisAdapter constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->client = $redis;
    }

    /**
     * @param $queues
     * @param $timeout
     * @return array
     */
    public function blPop($queues, $timeout)
    {
        return $this->client->blPop($queues, $timeout);
    }

    /**
     * @param $key
     * @param array $messages
     * @return int
     */
    public function rPush($key, array $messages)
    {
        return $this->client->rPush($key, $messages);
    }

    /**
     * @param $channels
     * @param $callback
     * @return mixed|void
     */
    public function subscribe($channels, $callback)
    {
        return $this->client->subscribe($channels, $callback);
    }

    /**
     * @param $channel
     * @param $message
     * @return int|mixed
     */
    public function publish($channel, $message)
    {
       return $this->client->publish($channel, $message);
    }

    /**
     * @return \Redis
     */
    public function getClient()
    {
        return $this->client;
    }
}