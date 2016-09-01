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
    /**
     * @var The socket Resource.
     */
    protected $socket;

    /**
     * Init the socket.
     *
     * @param Resource $socket The socket resource.
     */
    public function __construct($socket)
    {
        $this->socket = $socket;
    }

    /**
     * Accept the socket stream and return the connection.
     *
     * @return Generator
     */
    public function accept()
    {
        echo "received request from ", stream_socket_get_name($this->socket, 0), "\n";

        yield waitForRead($this->socket);
        yield retval(new Socket(stream_socket_accept($this->socket, 0)));
    }

    /**
     * Read the socket stream.
     *
     * @param int $size The read size.
     * @return Generator
     */
    public function read($size)
    {
        yield waitForRead($this->socket);
        yield retval(fread($this->socket, $size));
    }

    /**
     * Write the socket stream.
     *
     * @param string $string The response message.
     * @return Generator
     */
    public function write($string)
    {
        yield waitForWrite($this->socket);
        fwrite($this->socket, $string);
    }

    /**
     * Close the socket stream.
     */
    public function close()
    {
        @fclose($this->socket);
    }
}

// end of script
