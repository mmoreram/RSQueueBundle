<?php

namespace Mmoreram\RSQueueBundle\Services;

use Mmoreram\RSQueueBundle\Exception\LockException;
use Symfony\Component\Process\Process;

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

        if (
            $this->processExists($content['pid']) &&
            $content['stime'] == $this->getProcessStartTime($content['pid'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $pid
     *
     * @return string
     * @throws LockException
     */
    private function getProcessStartTime($pid)
    {
        $cmd = spritnf('ps -p %s -wo lstart | tail -n 1', $pid);
        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            $message = sprintf('Command "%s" is unsuccessfull with error: %s', $cmd, $process->getErrorOutput());
            throw new LockException($message);
        }

        return trim($process->getOutput());
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
        $content = json_decode(@file_get_contents($file), true);

        if (
            !is_array($content) ||
            !isset($content['pid'])
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
        $pid = getmypid();
        $content = json_encode([
            'pid' => $pid,
            'stime' => $this->getProcessStartTime($pid),
        ]);

        if (@file_put_contents($file, $content) === false) {
            throw new LockException(sprintf('Cant write "%s" file.', $file));
        }
    }
}