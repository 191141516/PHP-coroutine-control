<?php

namespace Server;

use Coroutine\Scheduler;
use Coroutine\Task;
use Coroutine\CoroutineReturnValue;
use Coroutine\SystemCall;


class Server
{
    protected $port;

    function __construct($port)
    {
        $this->port = $port;
    }

    public function start() {
        $port = $this->port;
        echo "Starting server at port $port...\n";

        $socket = stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
        if (!$socket) throw new Exception($errStr, $errNo);

        stream_set_blocking($socket, 0);

        $socket = new CoSocket($socket);
        while (true) {
            yield newTask(
                $this->handleClient(yield $socket->accept())
            );
        }
    }

    protected function handleClient($socket) {
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

}










// end of script
