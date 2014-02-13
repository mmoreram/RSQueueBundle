<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Factory;

use Mmoreram\RSQueueBundle\Factory\SerializerFactory;
use Mmoreram\RSQueueBundle\Serializer\JsonSerializer;
use Mmoreram\RSQueueBundle\Serializer\PHPSerializer;
use Mmoreram\RSQueueBundle\Tests\Factory\Serializer\FooSerializer;
use Mmoreram\RSQueueBundle\Exception\SerializerNotImplementsInterfaceException;
use Mmoreram\RSQueueBundle\Exception\SerializerNotFoundException;

/**
 * Tests SerializerFactory class
 */
class SerializerFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests PHP serializer load
     */
    public function testPHPGet()
    {
        $serializerFactory = new SerializerFactory('PHP');
        $this->assertInstanceOf('\\Mmoreram\\RSQueueBundle\\Serializer\\PHPSerializer', $serializerFactory->get());
    }

    /**
     * Tests Json serializer load
     */
    public function testJsonGet()
    {
        $serializerFactory = new SerializerFactory('Json');
        $this->assertInstanceOf('\\Mmoreram\\RSQueueBundle\\Serializer\\JsonSerializer', $serializerFactory->get());
    }

    /**
     * Tests class or type not found
     */
    public function testSerializerNotFound()
    {
        $serializerFactory = new SerializerFactory('\\Mmoreram\\RSQueueBundle\\Tests\\Factory\\Serializer\\EngongaSerializer');

        try {

            $serializerFactory->get();
        } catch (SerializerNotFoundException $expected) {
            return;
        }

        $this->fail('An expected SerializerNotFoundException exception has not been raised.');
    }

    /**
     * Tests class found with not implementation of SerializerInterface
     */
    public function testSimpleNotImplementingInterfaceFound()
    {
        $serializerFactory = new SerializerFactory('\\Mmoreram\\RSQueueBundle\\Serializer\\PHPSerializer');
        $this->assertInstanceOf('\\Mmoreram\\RSQueueBundle\\Serializer\\PHPSerializer', $serializerFactory->get());
    }

    /**
     * Tests class found with not implementation of SerializerInterface
     */
    public function testNotImplementingInterfaceFound()
    {
        $serializerFactory = new SerializerFactory('\\Mmoreram\\RSQueueBundle\\Tests\\Factory\\Serializer\\FooSerializer');

        try {

            $serializerFactory->get();
        } catch (SerializerNotImplementsInterfaceException $expected) {
            return;
        }

        $this->fail('An expected SerializerNotImplementsInterfaceException exception has not been raised.');
    }
}
