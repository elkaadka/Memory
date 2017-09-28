<?php
namespace Kanel\MemoryUsage;

class MemoryUsage
{
	protected $memory;
	protected $realMemory;
	protected $memoryPeak;
	protected $realMemoryPeak;

	/**
	 * @return float
	 */
	public function getMemory(): float
	{
		return $this->memory;
	}

	/**
	 * @param float $memory
	 * @return MemoryUsage
	 */
	public function setMemory(float $memory): MemoryUsage
	{
		$this->memory = $memory;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRealMemory(): float
	{
		return $this->realMemory;
	}

	/**
	 * @param float $realMemory
	 * @return MemoryUsage
	 */
	public function setRealMemory(float $realMemory): MemoryUsage
	{
		$this->realMemory = $realMemory;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getMemoryPeak(): float
	{
		return $this->memoryPeak;
	}

	/**
	 * @param float $memoryPeak
	 * @return MemoryUsage
	 */
	public function setMemoryPeak(float $memoryPeak): MemoryUsage
	{
		$this->memoryPeak = $memoryPeak;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getRealMemoryPeak(): float
	{
		return $this->realMemoryPeak;
	}

	/**
	 * @param float $realMemoryPeak
	 * @return MemoryUsage
	 */
	public function setRealMemoryPeak(float $realMemoryPeak): MemoryUsage
	{
		$this->realMemoryPeak = $realMemoryPeak;

		return $this;
	}



}