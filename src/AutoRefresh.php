<?php

namespace Glw\AutoRefresh;

use React\EventLoop\Factory;
use React\Socket\Server;
use React\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer as RatchetHttpServer;
use Ratchet\WebSocket\WsServer as RatchetWsServer;

class AutoRefresh
{
    public static function runServer(array $watchedFiles)
    {
        $loop = Factory::create();
        $socket = new Server('127.0.0.1:8080', $loop);

        $httpServer = new HttpServer(new RatchetWsServer(new WebSocketServer($watchedFiles)));
        $httpServer->listen($socket);

        $loop->run();
    }
}