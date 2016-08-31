<?php

use Coroutine\Scheduler;
use Server\Server;





require './bin/system.php';
require './AutoLoader.php';

$server = new Server(8000);

$scheduler = new Scheduler;
$scheduler->newTask($server->start());
$scheduler->run();
