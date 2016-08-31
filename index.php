<?php

use Coroutine;
use Coroutine\Scheduler;

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

function stackedCoroutine(Generator $gen) {
    $stack = new SplStack;
    $exception = null;

    while (true) {
        try {
            if ($exception) {
                $gen->throw($exception);
                $exception = null;
                continue;
            }

            $value = $gen->current();

            if ($value instanceof Generator) {
                $stack->push($gen);
                $gen = $value;
                continue;
            }

            $isReturnValue = $value instanceof CoroutineReturnValue;
            if (!$gen->valid() || $isReturnValue) {
                if ($stack->isEmpty()) {
                    return;
                }

                $gen = $stack->pop();
                $gen->send($isReturnValue ? $value->getValue() : NULL);
                continue;
            }

            try {
                $sendValue = (yield $gen->key() => $value);
            } catch (Exception $e) {
                $gen->throw($e);
                continue;
            }

            $gen->send($sendValue);
        } catch (Exception $e) {
            if ($stack->isEmpty()) {
                throw $e;
            }

            $gen = $stack->pop();
            $exception = $e;
        }
    }
}


function server($port) {
    echo "Starting server at port $port...\n";

    $socket = stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
    if (!$socket) throw new Exception($errStr, $errNo);

    stream_set_blocking($socket, 0);

    $socket = new CoSocket($socket);
    while (true) {
        yield newTask(
            handleClient(yield $socket->accept())
        );
    }
}

function handleClient($socket) {
    $data = (yield $socket->read(8192));

    $msg = "Received following request:\n\n$data";
    $msgLength = strlen($msg);

    $response = <<<RES
HTTP/1.1 200 OK\r\n
Content-Type: text/plain\r\n
Content-Length: $msgLength\r\n
Connection: close\r\n
\r\n
$msg
RES;

    yield $socket->write($response);
    yield $socket->close();
}




require './AutoLoader.php';


$scheduler = new Scheduler;
$scheduler->newTask(server(8000));
$scheduler->run();

// function echoTimes($msg, $max) {
//     for ($i = 1; $i <= $max; ++$i) {
//         echo "$msg iteration $i\n";
//         yield;
//     }
// }

// function t() {
//     yield echoTimes('foo', 10); // print foo ten times
//     echo "---\n";
//     yield echoTimes('bar', 5); // print bar five times
//     yield; // force it to be a coroutine
// }

// function task() {
//     try {
//         yield killTask(500);
//     } catch (Exception $e) {
//         echo 'Tried to kill task 500 but failed: ', $e->getMessage(), "\n";
//     }
// }

// // error_reporting(E_ALL ^ E_WARNING);

// $scheduler = new Scheduler;
// $scheduler->newTask(t());
// $scheduler->run();

/*
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

function childTask() {
    $tid = (yield getTaskId());
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}

function task() {
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));

    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;
 
        if ($i == 3) {
            yield killTask($childTid);
        }


    }
}

$scheduler = new Scheduler;
$scheduler->newTask(task());
$scheduler->run();

// $scheduler = new Scheduler;

// $scheduler->newTask(task1(10));
// $scheduler->newTask(task2(5));

// $scheduler->run();*/