![build](https://travis-ci.org/elkaadka/MemoryUsage.svg?branch=master)

A simple Memory usage tracking class to benchmark functions or code execution memory consumption

#How it works :

 - Start the memory tracking
     ```
         MemoryUsage::start();     
      ```
 - Mark a place as a lap (the memory tracking will continue counting after returning the difference between the start and this lap)
   ```
       $usage = MemoryUsage::lap();
    ```
 - If you want the memory used between this lap and the last one, send the following constant as a parameter:
   ```
       $usage = MemoryUsage::lap(MemoryUsage::FROM_LAST_LAP);
    ```
 - To stop the memory usage tracking and get the memory used from the beginning (the start)
    ```
        $usage = MemoryUsage::stop();
     ```
 - To stop the memory tracking and get the memory used from the last lap 
    ```
        $usage = MemoryUsage::stop(MemoryUsage::FROM_LAST_LAP);
    ```
 - To get all the memory used recorded
    ```
        $memoryUsage = MemoryUsage::getMemoryUsages();
    ```
 - To get the history of tracked memory
    ```
        $memoryUsage = MemoryUsage::getHistory();
    ```