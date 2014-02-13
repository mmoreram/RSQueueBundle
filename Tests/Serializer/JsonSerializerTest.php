<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Serializer;

use Mmoreram\RSQueueBundle\Serializer\JsonSerializer;

/**
 * Tests JsonSerializer class
 */
class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests json serializer apply method
     */
    public function testApply()
    {
        $serializer = new JsonSerializer();
        $data = array(
            'foo'   =>  'foodata',
            'engonga'   =>  'someengongadata',
        );
        $serializedData = $serializer->apply($data);
        $this->assertEquals($serializedData, '{"foo":"foodata","engonga":"someengongadata"}');
    }

    /**
     * Test json serializer revert method
     */
    public function testRevert()
    {
        $serializer = new JsonSerializer();
        $data = '{"foo":"foodata","engonga":"someengongadata"}';
        $unserializedData = $serializer->revert($data);
        $this->assertEquals($unserializedData, array(
            'foo'   =>  'foodata',
            'engonga'   =>  'someengongadata',
        ));
    }
}
