<?php

namespace Server;

/**
 * Class Socket.
 * The socket process.
 *
 * @category PHP
 * @package  Server
 * @author   Arno [<arnoliu@tencent.com> | <1048434786@qq.com>]
 */
class Socket
{
    protected $socket;

    public function __construct($socket)
    {
        $this->socket = $socket;
    }

    public function accept()
    {
        echo "received request from ", stream_socket_get_name($this->socket, 0), "\n";

        yield waitForRead($this->socket);
        yield retval(new Socket(stream_socket_accept($this->socket, 0)));
    }

    public function read($size)
    {
        yield waitForRead($this->socket);
        yield retval(fread($this->socket, $size));
    }

    public function write($string)
    {
        yield waitForWrite($this->socket);
        fwrite($this->socket, $string);
    }

    public function close()
    {
        @fclose($this->socket);
    }
}

// end of script
