<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Serializer;

use Mmoreram\RSQueueBundle\Serializer\Interfaces\SerializerInterface;

/**
 * Implementation of Json Serializer
 */
class JsonSerializer implements SerializerInterface
{

    /**
     * Given any kind of object, apply serialization
     *
     * @param Mixed $unserializedData Data to serialize
     *
     * @return string
     */
    public function apply($unserializedData)
    {
        return json_encode($unserializedData);
    }

    /**
     * Given any kind of object, apply serialization
     *
     * @param String $serializedData Data to unserialize
     *
     * @return mixed
     */
    public function revert($serializedData)
    {
        return json_decode($serializedData, true);
    }
}
