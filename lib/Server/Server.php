<?php

namespace Server;

/**
 * Class Server.
 * The PHP inner server.
 *
 * @category PHP
 * @package  Server
 * @author   Arno [<arnoliu@tencent.com> | <1048434786@qq.com>]
 */
class Server
{
    /**
     * @var The listen port.
     */
    protected $port;

    /**
     * Init the port.
     *
     * @param int $port The listen port.
     */
    function __construct($port)
    {
        $this->port = $port;
    }

    /**
     * Start the service.
     *
     * @return Generator
     */
    public function start()
    {
        $port = $this->port;
        echo "Starting server at port $port...\n";

        $socket = stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
        if (!$socket) throw new Exception($errStr, $errNo);

        stream_set_blocking($socket, 0);

        $socket = new Socket($socket);
        while (true) {
            yield newTask(
                $this->handleClient(yield $socket->accept())
            );
        }
    }

    /**
     * The socket client handler.
     *
     * @param Resource $socket The socket resource that need to handle.
     * @return Generator
     */
    protected function handleClient($socket)
    {
        $data = (yield $socket->read(8192));

        $body = "Received following request:\n\n$data";
        $bodyLength = strlen($body);

        $response = array();

        array_push($response, "HTTP/1.1 200 OK\r\n");
        array_push($response, "Content-Type: text/plain\r\n");
        array_push($response, "Content-Length: $bodyLength\r\n");
        array_push($response, "Connection: close\r\n");
        array_push($response, "\r\n");
        array_push($response, "$body");

        $response = implode($response, '');

        yield $socket->write($response);
        yield $socket->close();
    }
}

// end of script
