<?php

namespace Kanel\MemoryUsage;

use PHPUnit\Framework\TestCase;
use Kanel\MemoryUsage\Exception\MemoryUsageException;

function memory_get_usage(bool $real_usage = false): float
{
    if ($real_usage === true) {
        return ++MemoryUsageTest::$memory;
    } else {
		return MemoryUsageTest::$memory += 2;

	}
}

function memory_get_peak_usage(bool $real_usage = false): float
{
	if ($real_usage === true) {
		return ++MemoryUsageTest::$memory;
	} else {
		return MemoryUsageTest::$memory += 2;
	}
}

class MemoryUsageTest extends TestCase
{
    public static $memory;
    protected $timer;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        self::$memory = 0;
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testStart()
    {
        MemoryBench::start();
        $this->assertEquals(MemoryBench::getStatus(), MemoryBench::STARTED);
    }

    public function testStopFail()
    {
        $this->expectException(MemoryUsageException::class);
        MemoryBench::reset();
        MemoryBench::stop();
    }

    public function testStop()
    {
        MemoryBench::start();
        $memoryUsage = MemoryBench::stop();
		$this->assertEquals(MemoryBench::getStatus(), MemoryBench::STOPPED);
		$this->assertInstanceOf(MemoryUsage::class, $memoryUsage);
		$this->assertEquals(6, $memoryUsage->getMemory());
		$this->assertEquals(6, $memoryUsage->getRealMemory());
		$this->assertEquals(6, $memoryUsage->getMemoryPeak());
		$this->assertEquals(6, $memoryUsage->getRealMemoryPeak());
	}

    public function testStopWithLaps()
    {
        MemoryBench::start();
        MemoryBench::lap();
        MemoryBench::lap();
		$memoryUsage = MemoryBench::stop(MemoryBench::FROM_LAST_LAP);
		$this->assertEquals(MemoryBench::getStatus(), MemoryBench::STOPPED);
		$this->assertInstanceOf(MemoryUsage::class, $memoryUsage);
		$this->assertEquals(6, $memoryUsage->getMemory());
		$this->assertEquals(6, $memoryUsage->getRealMemory());
		$this->assertEquals(6, $memoryUsage->getMemoryPeak());
		$this->assertEquals(6, $memoryUsage->getRealMemoryPeak());
    }

    public function testLapFail()
    {
        $this->expectException(MemoryUsageException::class);
        MemoryBench::reset();
        MemoryBench::lap();
    }

    public function testLap()
    {
        MemoryBench::start();
		$memoryUsage = MemoryBench::lap();
		$this->assertInstanceOf(MemoryUsage::class, $memoryUsage);
		$this->assertEquals(6, $memoryUsage->getMemory());
		$this->assertEquals(6, $memoryUsage->getRealMemory());
		$this->assertEquals(6, $memoryUsage->getMemoryPeak());
		$this->assertEquals(6, $memoryUsage->getRealMemoryPeak());

        $memoryUsage = MemoryBench::lap();
		$this->assertInstanceOf(MemoryUsage::class, $memoryUsage);
		$this->assertEquals(12, $memoryUsage->getMemory());
		$this->assertEquals(12, $memoryUsage->getRealMemory());
		$this->assertEquals(12, $memoryUsage->getMemoryPeak());
		$this->assertEquals(12, $memoryUsage->getRealMemoryPeak());

        $memoryUsage = MemoryBench::lap();
		$this->assertInstanceOf(MemoryUsage::class, $memoryUsage);
		$this->assertEquals(18, $memoryUsage->getMemory());
		$this->assertEquals(18, $memoryUsage->getRealMemory());
		$this->assertEquals(18, $memoryUsage->getMemoryPeak());
		$this->assertEquals(18, $memoryUsage->getRealMemoryPeak());
    }

    public function testGetHistory()
    {
        MemoryBench::start();
        MemoryBench::lap();
        MemoryBench::lap();
        MemoryBench::stop();
        $history = MemoryBench::getHistory();
        $this->assertEquals(count($history), 3);
        foreach ($history as $item) {
			$this->assertInstanceOf(MemoryUsage::class, $item);
		}
    }

	public function testGetLastBench()
	{
		MemoryBench::start();
		MemoryBench::lap();
		MemoryBench::lap();
		MemoryBench::stop();
		$memoryUsage = MemoryBench::getLastBench();
		$this->assertInstanceOf(MemoryUsage::class, $memoryUsage);
		$this->assertEquals(18, $memoryUsage->getMemory());
		$this->assertEquals(18, $memoryUsage->getRealMemory());
		$this->assertEquals(18, $memoryUsage->getMemoryPeak());
		$this->assertEquals(18, $memoryUsage->getRealMemoryPeak());
	}
}