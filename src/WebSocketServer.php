<?php

namespace Glw\AutoRefresh;

class WebSocketServer
{
    private $watchedFiles;

    public function __construct(array $watchedFiles)
    {
        $this->watchedFiles = $watchedFiles;
    }

    public function start()
    {
        $address = '127.0.0.1';
        $port = 8080;

        $server = stream_socket_server("tcp://$address:$port", $errno, $errorMessage);

        if (!$server) {
            die("Error: $errorMessage");
        }

        echo "Server started on $address:$port\n";

        $fileWatcher = new FileWatcher($this->watchedFiles);

        while ($client = stream_socket_accept($server)) {
            echo "Client connected\n";

            // Start watching files
            while (!$fileWatcher->watch()) {
                // Wait for file changes
            }

            echo "File changed, sending refresh signal\n";
            fwrite($client, "reload");
            fclose($client);
        }

        fclose($server);
    }
}