<?php

namespace Kanel\MemoryUsage;

use PHPUnit\Framework\TestCase;
use Kanel\MemoryUsage\Exception\MemoryUsageException;

function memory_get_usage(bool $real_usage = false): float
{
    if ($real_usage === true) {
        return ++MemoryUsageTest::$memory;
    }

    throw new \Exception('wrong microtime usage in Timer context');
}

class MemoryUsageTest extends TestCase
{
    public static $memory ;
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
        MemoryUsage::start();
        $this->assertEquals(MemoryUsage::getStatus(), MemoryUsage::STARTED);
        $this->assertEquals(count(MemoryUsage::getMemoryUsages()), 1);
    }

    public function testStopFail()
    {
        $this->expectException(MemoryUsageException::class);
        MemoryUsage::reset();
        MemoryUsage::stop();
    }

    public function testStop()
    {
        MemoryUsage::start();
        MemoryUsage::lap();
        MemoryUsage::lap();
        $memoryUsed = MemoryUsage::stop();
        $this->assertEquals(MemoryUsage::getStatus(), MemoryUsage::STOPPED);
        $this->assertEquals($memoryUsed, 3);
        $this->assertEquals(count(MemoryUsage::getMemoryUsages()), 4);
    }

    public function testStopWithLaps()
    {
        MemoryUsage::start();
        MemoryUsage::lap();
        MemoryUsage::lap();
        $memoryUsed = MemoryUsage::stop(MemoryUsage::FROM_LAST_LAP);
        $this->assertEquals(MemoryUsage::getStatus(), MemoryUsage::STOPPED);
        $this->assertEquals($memoryUsed, 1);
        $this->assertEquals(count(MemoryUsage::getMemoryUsages()), 4);
    }

    public function testLapFail()
    {
        $this->expectException(MemoryUsageException::class);
        MemoryUsage::reset();
        MemoryUsage::lap();
    }

    public function testLap()
    {
        MemoryUsage::start();
        $memoryUsed = MemoryUsage::lap();
        $this->assertEquals($memoryUsed, 1);
        $memoryUsed = MemoryUsage::lap();
        $this->assertEquals($memoryUsed, 2);
        $memoryUsed = MemoryUsage::lap();
        $this->assertEquals($memoryUsed, 3);
        $memoryUsed = MemoryUsage::lap();
        $this->assertEquals($memoryUsed, 4);
        $memoryUsed = MemoryUsage::lap(MemoryUsage::FROM_LAST_LAP);
        $this->assertEquals($memoryUsed, 1);
    }

}