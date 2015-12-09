<?php

/*
 * This file is part of the FOSRestBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mmoreram\RSQueueBundle\Tests\DependencyInjection;

use Mmoreram\RSQueueBundle\DependencyInjection\RSQueueExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * RSQueueExtension test.
 *
 * @author Ener-Getick <egetick@gmail.com>
 */
class RSQueueExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var RSQueueExtension
     */
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new RSQueueExtension();
    }

    public function testExtension() {
        $config = array();
        $this->extension->load($config, $this->container);
    }
}
