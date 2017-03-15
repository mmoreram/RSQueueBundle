<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 04/12/16
 * Time: 12:23
 */

namespace Mmoreram\RSQueueBundle\Redis;

/**
 * Class PredisClientAdapter
 * @package Mmoreram\RSQueueBundle\Redis
 */
class PredisClientAdapter implements AdapterInterface
{

    /**
     * @var \Predis\Client
     */
    protected $client;

    /**
     * PredisClientAdapter constructor.
     * @param \Predis\Client $client
     */
    public function __construct(\Predis\Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $queues
     * @param $timeout
     * @return array
     */
    public function blPop($queues, $timeout)
    {
        return $this->client->blpop($queues, $timeout);
    }

    /**
     * @param $key
     * @param array $messages
     * @return int
     */
    public function rPush($key, array $messages)
    {
        return $this->client->rpush($key, $messages);
    }

    /**
     * @param $channels
     * @param $callback
     */
    public function subscribe($channels, $callback)
    {
        $pubSub = $this->client->pubSubLoop();
        $pubSub->subscribe($channels);
        foreach ($pubSub as $message) {
            switch ($message->kind) {
                case 'message':
                    $callback($pubSub->getClient(), $message->channel, $message->payload);
                    break;
            }

        }
        unset($pubSub);

    }

    /**
     * @param $channel
     * @param $message
     * @return int
     */
    public function publish($channel, $message)
    {
        return $this->client->publish($channel, $message);
    }

    /**
     * @return \Predis\Client
     */
    public function getClient()
    {
       return $this->client;
    }
}