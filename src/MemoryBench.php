<?php

namespace Kanel\MemoryUsage;

use Kanel\MemoryUsage\Exception\MemoryUsageException;

/**
 * Class MemoryUsage
 * @package Kanel\MemoryUsage
 */
class MemoryBench
{
    const FROM_LAST_LAP = 'last_lap';
    const NONE = 'none';
    const STARTED = 'started';
    const STOPPED = 'stopped';

	protected static $memory;
	protected static $realMemory;
	protected static $memoryPeak;
	protected static $realMemoryPeak;

	protected static $status;
    protected static $statistics;
    protected static $laps;

    /**
     * function that starts/restarts the memory usage tracking
     */
    public static function start()
    {
        self::reset();
		self::record();
        self::$status = self::STARTED;
    }

    /**
     * Functions that simulates a lap
     * returns the memory used between the time it started tracking and the time lap was called or between this lap and the previous one
     * @param string $fromLastLap
     * @return MemoryUsage
     * @throws MemoryUsageException
     */
    public static function lap(string $fromLastLap = ''): MemoryUsage
    {
        if (self::$status !== self::STARTED) {
            throw new MemoryUsageException('Timer is not started', 500);
        }

        self::$laps++;
		self::record();
        return self::statistics($fromLastLap);
    }

	/**
	 * Record the memory used
	 */
    private static function record()
	{
		self::$memory[] = memory_get_usage();
		self::$realMemory[] = memory_get_usage(true);
		self::$memoryPeak[] = memory_get_peak_usage();
		self::$realMemoryPeak[] = memory_get_peak_usage(true);
	}

    /**
     * Functions that stops the memory tracking
     * returns the memory used between the start and the stop or between the stop and the previous lap
     * @param string $fromLastLap
     * @return MemoryUsage
     * @throws MemoryUsageException
     */
    public static function stop(string $fromLastLap = ''): MemoryUsage
    {
        if (self::$status !== self::STARTED) {
            throw new MemoryUsageException('Timer is not started', 500);
        }

        self::$laps++;
        self::record();
        self::$status = self::STOPPED;

        return self::statistics($fromLastLap);
    }

    /**
     * calculates the memory used between the start and the last lap or stop
     * @param string $fromLastLap
     * @return MemoryUsage
     */
    protected static function statistics(string $fromLastLap = ''): MemoryUsage
    {
        if ($fromLastLap !== self::FROM_LAST_LAP || self::$laps < 1 ) {
			$fromIndex = 0;
        } else {
			$fromIndex = self::$laps - 1;
        }

		$memoryUsed = self::$memory[self::$laps] - self::$memory[$fromIndex];
		$realMemory = self::$realMemory[self::$laps] - self::$realMemory[$fromIndex];
		$memoryPeak = self::$memoryPeak[self::$laps] - self::$memoryPeak[$fromIndex];
		$realMemoryPeak = self::$realMemoryPeak[self::$laps] - self::$realMemoryPeak[$fromIndex];

        $memoryUsage = new MemoryUsage();
		$memoryUsage
			->setMemory($memoryUsed)
			->setRealMemory($realMemory)
			->setMemoryPeak($memoryPeak)
			->setRealMemoryPeak($realMemoryPeak)
		;

        self::$statistics[] = $memoryUsage;
        return $memoryUsage;
    }

    /**
     * Resets everything
     */
    public static function reset()
    {
        self::$laps = 0;
		self::$memory = [];
		self::$realMemory = [];
		self::$memoryPeak = [];
		self::$realMemoryPeak = [];
        self::$status = self::NONE;
        self::$statistics = [];
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