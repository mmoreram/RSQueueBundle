<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Factory;

use Mmoreram\RSQueueBundle\Exception\SerializerNotImplementsInterfaceException;
use Mmoreram\RSQueueBundle\Exception\SerializerNotFoundException;

/**
 * Interface for any kind of serialization
 */
class SerializerFactory
{

    /**
     * @var SerializerInterface
     *
     * Serializer
     */
    protected $serializerType;

    /**
     * Construct method
     *
     * @param string $serializerType Serializer type
     *
     * Construct method
     */
    public function __construct($serializerType)
    {
        $this->serializerType = $serializerType;
    }

    /**
     * Generate new Serializer
     *
     * @return SerializerInterface Generated Serializer
     */
    public function get()
    {
        if (class_exists($this->serializerType)) {

            if (in_array('Mmoreram\\RSQueueBundle\\Serializer\\Interfaces\\SerializerInterface', class_implements($this->serializerType))) {
                return new $this->serializerType;
            } else {

                throw new SerializerNotImplementsInterfaceException;
            }
        }

        $composedSerializerNamespace = '\\Mmoreram\\RSQueueBundle\\Serializer\\' . $this->serializerType . 'Serializer';

        if (class_exists($composedSerializerNamespace)) {
            return new $composedSerializerNamespace;
        }

        throw new SerializerNotFoundException;
    }
}
