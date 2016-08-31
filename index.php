<?php

use Coroutine\Scheduler;
use Coroutine\Task;
use Coroutine\CoroutineReturnValue;
use Coroutine\SystemCall;
use Server\Server;

//use \SplStack;


// system call
function getTaskId() {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) {

            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        }
    );
}

function newTask(Generator $coroutine) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

function killTask($tid) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            if ($scheduler->killTask($tid)) {
                $scheduler->schedule($task);
            } else {
                throw new InvalidArgumentException('Invalid task ID!');
            }
        }
    );
}

function waitForRead($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForRead($socket, $task);
        }
    );
}

function waitForWrite($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForWrite($socket, $task);
        }
    );
}

function retval($value) {
    return new CoroutineReturnValue($value);
}




require './AutoLoader.php';

$server = new Server(8000);

$scheduler = new Scheduler;
$scheduler->newTask($server->start());
$scheduler->run();
