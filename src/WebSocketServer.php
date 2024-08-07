<?php

namespace Glw\AutoRefresh;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $watchedFiles;

    public function __construct(array $watchedFiles)
    {
        $this->clients = new \SplObjectStorage;
        $this->watchedFiles = $watchedFiles;
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) 
    {
        $this->clients->detach($conn);
        echo "Connection closed: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}