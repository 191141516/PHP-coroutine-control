<?php

require './AutoLoader.php';

// system call
function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}








// task
function task1($max) {
    $tid = (yield getTaskId()); // <-- here's the syscall!
    var_dump($tid);
    for ($i = 1; $i <= $max; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield;
    }
}
 
function task2($max) {
    $tid = (yield getTaskId()); // <-- here's the syscall!
    var_dump($tid);
    for ($i = 1; $i <= $max; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield;
    }
}
 
$scheduler = new Scheduler;
 
$scheduler->newTask(task1(10));
$scheduler->newTask(task2(5));
 
$scheduler->run();