<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreramerino\RSQueueBundle\Serialization;

/**
 * Implementation of PHP native Serializer 
 */
class PHPSerializer implements SerializerInterface
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
        return serialize($unserializedData);
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
        return unserialize($serializedData);
    }
}