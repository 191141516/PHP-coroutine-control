<?php

use Coroutine\Scheduler;
use Server\Server;

require './system.functions.php';
require '../AutoLoader.php';

$file = isset($argv[1]) ? $argv[1] : '';

if ($file === '' || isset($argv[2])) {
    serverInfo();
}

if ($file === 'list') {
    serverList();
}


// $server = new Server(8000);

// $scheduler = new Scheduler;
// $scheduler->newTask($server->start());
// $scheduler->run();

function serverList()
{
    $confDir = dirname(__dir__) . DIRECTORY_SEPARATOR . 'conf';

    $source = fopen($confDir, 'r');
    $list = readdir();
    fclose($source);

    var_dump($confDir);
}

function serverInfo()
{
    echo "***********************************" . PHP_EOL;
    echo "*  Welcome to use the PHP server  *" . PHP_EOL;
    echo "***********************************" . PHP_EOL;
    echo "Welcome to use the PHP server, we can help you to monitor your PHP server!" . PHP_EOL;
    echo "Command list:" . PHP_EOL;
    echo "Input server name and command just like: php server.php myServerName" . PHP_EOL;
    echo "If you want to stop the server, please use: ctrl + c" . PHP_EOL;
    echo "If you want to know server list that you can input: php server.php list" . PHP_EOL;

    exit;
}
