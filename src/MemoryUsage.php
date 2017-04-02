<?php

namespace Kanel\MemoryUsage;

use Kanel\MemoryUsage\Exception\MemoryUsageException;

/**
 * Class MemoryUsage
 * @package Kanel\MemoryUsage
 */
class MemoryUsage
{
    const FROM_LAST_LAP = 'last_lap';
    const NONE = 'none';
    const STARTED = 'started';
    const STOPPED = 'stopped';

    protected static $memory;
    protected static $status;
    protected static $statistics;
    protected static $laps;

    /**
     * function that starts/restarts the memory usage tracking
     */
    public static function start()
    {
        self::reset();
        self::$memory[] = memory_get_usage(true);
        self::$status = self::STARTED;
    }

    /**
     * Functions that simulates a lap
     * returns the memory used between the time it started tracking and the time lap was called or between this lap and the previous one
     * @param string $fromLastLap
     * @return float
     * @throws MemoryUsageException
     */
    public static function lap(string $fromLastLap = ''): float
    {
        if (self::$status !== self::STARTED) {
            throw new MemoryUsageException('Timer is not started', 500);
        }

        self::$laps++;
        self::$memory[] = memory_get_usage(true);

        return self::statistics($fromLastLap);
    }

    /**
     * Functions that stops the memory tracking
     * returns the memory used between the start and the stop or between the stop and the previous lap
     * @param string $fromLastLap
     * @return mixed
     * @throws MemoryUsageException
     */
    public static function stop(string $fromLastLap = ''): float
    {
        if (self::$status !== self::STARTED) {
            throw new MemoryUsageException('Timer is not started', 500);
        }

        self::$laps++;
        self::$memory[] = memory_get_usage(true);
        self::$status = self::STOPPED;

        return self::statistics($fromLastLap);
    }

    /**
     * calculates the memory used between the start and the last lap or stop
     * @param string $fromLastLap
     * @return float
     */
    protected static function statistics(string $fromLastLap = ''): float
    {
        if ($fromLastLap !== self::FROM_LAST_LAP || self::$laps < 1 ) {
            $memoryUsed = self::$memory[self::$laps] - self::$memory[0];
        } else {
            $memoryUsed = self::$memory[self::$laps] - self::$memory[self::$laps - 1];
        }

        self::$statistics[] = $memoryUsed;

        return $memoryUsed;
    }

    /**
     * Resets everything
     */
    public static function reset()
    {
        self::$laps = 0;
        self::$memory = [];
        self::$status = self::NONE;
        self::$statistics = [];
    }

    /**
     * returns the list of times set (each lap creates an entry, a stop creates one)
     * @return array
     */
    public static function getMemoryUsages(): array
    {
        return self::$memory;
    }

    /**
     * returns all the metrics calculated throughout the Timer life
     * @return array
     */
    public static function getHistory(): array
    {
        return self::$statistics;
    }

    /**
     * gets the current status of the time :
     * @return string values are started|stopped
     */
    public static function getStatus(): string
    {
        return self::$status;
    }
}