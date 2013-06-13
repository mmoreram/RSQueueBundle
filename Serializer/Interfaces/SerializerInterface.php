<?php

/**
 * RSQueueBundle for Symfony2
 * 
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Serializer\Interfaces;

/**
 * Interface for any kind of serialization   
 */
interface SerializerInterface
{

    /**
     * Given any kind of object, apply serialization
     * 
     * @param Mixed $unserializedData Data to serialize
     * 
     * @return string
     */
    public function apply($unserializedData);


    /**
     * Given any kind of object, apply serialization
     * 
     * @param String $serializedData Data to unserialize
     * 
     * @return mixed
     */
    public function revert($serializedData);
}