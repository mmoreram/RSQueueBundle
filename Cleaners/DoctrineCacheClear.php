<?php

namespace Mmoreram\RSQueueBundle\Cleaners;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DoctrineCacheClear
 *
 * @package Mmoreram\RSQueueBundle\Cleaners
 */
class DoctrineCacheClear extends AbstractCleaner
{
    const PATH = 'Doctrine\Common\Persistence\ManagerRegistry';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DoctrineCacheClear constructor.
     *
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method that will clear container cache
     */
    public function cacheClear()
    {
        $this->container->get('doctrine')->getManager()->clear();
    }

    /**
     * @return string
     */
    public function getManagerClassPath()
    {
        return self::PATH;
    }
}
