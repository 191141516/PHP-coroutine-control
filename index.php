<?php

use Coroutine\Scheduler;
use Coroutine\Task;
use Coroutine\CoroutineReturnValue;

use Server\Server;






require './AutoLoader.php';

$server = new Server(8000);

$scheduler = new Scheduler;
$scheduler->newTask($server->start());
$scheduler->run();
