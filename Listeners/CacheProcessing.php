<?php

namespace Mmoreram\RSQueueBundle\Listeners;

use Mmoreram\RSQueueBundle\Cleaners;

/**
 * Class CacheProcessing
 *
 * @package Mmoreram\RSQueueBundle\Listeners
 */
class CacheProcessing
{
    /**
     * @var array
     */
    protected $cleanerContainer = [];

    /**
     * CacheProcessing constructor.
     *
     * @param array ...$cleaners
     */
    public function __construct(... $cleaners)
    {
        $this->cleanerContainer = $cleaners;
    }

    /**
     * Clearing cache in all inner container
     */
    public function cleanerProcessing()
    {
        foreach ($this->cleanerContainer as $item) {
            if ($item->ifClassExist($item->getManagerClassPath())) {
                $item->cacheClear();
            }
        }
    }
}
