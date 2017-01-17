<?php

namespace Mmoreram\RSQueueBundle\Services;

use Mmoreram\RSQueueBundle\Exception\LockException;

/**
 * Class LockHandler
 *
 * @package Mmoreram\RSQueueBundle\Services
 */
class LockHandler
{
    /**
     * @param string $file
     *
     * @return bool If lock already exists, returns false.
     * @throws LockException
     */
    public function lock($file)
    {
        if ($this->processLocked($file)) {
            return false;
        }

        $this->createLockFile($file);

        return true;
    }

    /**
     * @param string $file
     *
     * @throws LockException
     */
    public function unlock($file)
    {
        if (@unlink($file) === false) {
            throw new LockException(sprintf('Cant unlink file "%s".', $file));
        }
    }

    /**
     * @param string $file
     *
     * @return bool
     * @throws LockException
     */
    private function processLocked($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        $content = $this->readLockFile($file);
        $pid = $content['pid'];

        if ($this->processExists($pid)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $pid
     *
     * @return bool
     */
    private function processExists($pid)
    {
        return file_exists('/proc/'.$pid);
    }

    /**
     * @param string $file
     *
     * @return array
     * @throws LockException
     */
    private function readLockFile($file)
    {
        $content = json_decode(@file_get_contents($file));

        if (
            !is_array($content) ||
            !isset($content['id'])
        ) {
            throw new LockException(sprintf('File "%s" contains bad format.', $file));
        }

        return $content;
    }

    /**
     * @param $file
     *
     * @throws LockException
     */
    private function createLockFile($file)
    {
        $content = json_encode([
            'pid' => getmypid(),
        ]);

        if (@file_put_contents($file, $content) === false) {
            throw new LockException(sprintf('Cant write "%s" file.', $file));
        }
    }
}