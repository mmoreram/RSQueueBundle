<?php

/**
 * RSQueueBundle for Symfony2
 *
 * Marc Morera 2013
 */

namespace Mmoreram\RSQueueBundle\Tests\Resolver;

use Mmoreram\RSQueueBundle\Resolver\QueueAliasResolver;
use Mmoreram\RSQueueBundle\Exception\InvalidAliasException;

/**
 * Tests QueueAliasResolver class
 */
class QueueAliasResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Array
     *
     * Queue aliases setup
     */
    private $queueAliases = array(
        'myqueue1'  =>  'queues.myqueue1',
        'myqueue2'  =>  'queues.myqueue2',
    );

    /**
     * Tests get method
     */
    public function testGet()
    {
        $queueAliasResolver = new QueueAliasResolver($this->queueAliases);
        $this->assertEquals($queueAliasResolver->get('myqueue1'), $this->queueAliases['myqueue1']);
        $this->assertEquals($queueAliasResolver->get('myqueue2'), $this->queueAliases['myqueue2']);
    }


    /**
     * Test check method
     */
    public function testCheck()
    {
        $queueAliasResolver = new QueueAliasResolver($this->queueAliases);
        $this->assertEquals($queueAliasResolver->check('myqueue1'), true);

        try {

            $queueAliasResolver->check('myqueue3');
        } catch (InvalidAliasException $expected) {

            return;
        }

        $this->fail('An expected InvalidAliasException exception has not been raised.');
    }

    /**
     * Test get queue alias method
     */
    public function testGetQueueAlias()
    {
        $queueAliasResolver = new QueueAliasResolver($this->queueAliases);
        $this->assertEquals('myqueue1', $queueAliasResolver->getQueueAlias($this->queueAliases['myqueue1']));
        $this->assertEquals('myqueue2', $queueAliasResolver->getQueueAlias($this->queueAliases['myqueue2']));
    }

    /**
     * Test get queue aliases method
     */
    public function testGetQueueAliases()
    {
        $queueAliasResolver = new QueueAliasResolver($this->queueAliases);
        $this->assertEquals($this->queueAliases, $queueAliasResolver->getQueueAliases());
    }
}