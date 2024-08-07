<?php

namespace Glw\AutoRefresh;

class AutoRefresh
{
    public static function runServer(array $watchedFiles)
    {
        $server = new WebSocketServer($watchedFiles);
        $server->start();
    }
}