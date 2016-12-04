<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 04/12/16
 * Time: 12:14
 */

namespace Mmoreram\RSQueueBundle\Redis;


interface AdapterInterface
{

    /**
     * @param $queues
     * @param $timeout
     * @return mixed
     */
    public function blPop($queues, $timeout);

    /**
     * @param $key
     * @param array $messages
     * @return mixed
     */
    public function rPush($key, array $messages);

    /**
     * @param $channels
     * @param $callback
     * @return mixed
     */
    public function subscribe($channels, $callback);

    /**
     * @param $channel
     * @param $message
     * @return mixed
     */
    public function publish($channel, $message);

    /**
     * @return \Redis|\Predis\Client
     */
    public function getClient();

}