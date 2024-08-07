<?php
namespace Glw\AutoRefresh;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class AutoRefreshServer implements MessageComponentInterface {
    protected $clients;
    protected $watchedFiles;
    protected $lastModifiedTimes;

    public function __construct($watchedFiles = []) {
        $this->clients = new \SplObjectStorage;
        $this->watchedFiles = $watchedFiles;
        $this->lastModifiedTimes = [];

        foreach ($watchedFiles as $file) {
            if (file_exists($file)) {
                $this->lastModifiedTimes[$file] = filemtime($file);
            }
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    public function checkForChanges() {
        foreach ($this->watchedFiles as $file) {
            if (file_exists($file)) {
                $lastModifiedTime = filemtime($file);
                if ($lastModifiedTime !== $this->lastModifiedTimes[$file]) {
                    $this->lastModifiedTimes[$file] = $lastModifiedTime;
                    $this->notifyClients();
                }
            }
        }
    }

    protected function notifyClients() {
        foreach ($this->clients as $client) {
            $client->send('reload');
        }
    }

    public static function runServer($watchedFiles, $port = 8080) {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new self($watchedFiles)
                )
            ),
            $port
        );

        $loop = $server->loop;
        $loop->addPeriodicTimer(1, function () use ($server) {
            $server->app->checkForChanges();
        });

        $server->run();
    }
}