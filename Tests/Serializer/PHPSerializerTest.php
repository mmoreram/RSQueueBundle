<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Serializer;

use Mmoreram\RSQueueBundle\Serializer\PHPSerializer;

/**
 * Tests PHPSerializer class
 */
class PHPSerializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests php serializer apply method
     */
    public function testApply()
    {
        $serializer = new PHPSerializer();
        $data = array(
            'foo'   =>  'foodata',
            'engonga'   =>  'someengongadata',
        );
        $serializedData = $serializer->apply($data);
        $this->assertEquals($serializedData, 'a:2:{s:3:"foo";s:7:"foodata";s:7:"engonga";s:15:"someengongadata";}');
    }

    /**
     * Test php serializer revert method
     */
    public function testRevert()
    {
        $serializer = new PHPSerializer();
        $data = 'a:2:{s:3:"foo";s:7:"foodata";s:7:"engonga";s:15:"someengongadata";}';
        $unserializedData = $serializer->revert($data);
        $this->assertEquals($unserializedData, array(
            'foo'   =>  'foodata',
            'engonga'   =>  'someengongadata',
        ));
    }
}
