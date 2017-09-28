![build](https://travis-ci.org/elkaadka/MemoryUsage.svg?branch=master)

A simple Memory usage tracking class to benchmark functions or code execution memory consumption

#How it works :

 - Start the memory tracking
     ```
         MemoryUsage::start();     
      ```
 - Mark a place as a lap (the memory tracking will continue counting after returning the difference between the start and this lap)
   ```
       $memoryUsage = MemoryBench::lap();
    ```
    Where $memoryUsage is instance of class :
    ```
    class MemoryUsage
    {
            protected $memory;
            protected $realMemory;
            protected $memoryPeak;
            protected $realMemoryPeak;
    }

    ```
 - If you want the memory used between this lap and the last one, send the following constant as a parameter:
   ```
       $memoryUsage = MemoryBench::lap(MemoryBench::FROM_LAST_LAP);
    ```
 - To stop the memory usage tracking and get the memory used from the beginning (the start)
    ```
        $memoryUsage = MemoryBench::stop();
     ```
 - To stop the memory tracking and get the memory used from the last lap 
    ```
        $memoryUsage = MemoryBench::stop(MemoryBench::FROM_LAST_LAP);
    ```
 - To get the history of tracked memory
    ```
        $memoryUsages = MemoryBench::getHistory();
    ```
    Where $memoryUsages is an array of MemoryUsage classes