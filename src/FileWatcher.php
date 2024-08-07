<?php

namespace Glw\AutoRefresh;

class FileWatcher
{
    private $files = [];
    private $interval;

    public function __construct(array $files, $interval = 1)
    {
        $this->files = $files;
        $this->interval = $interval;
    }

    public function watch()
    {
        $lastModified = array_map('filemtime', $this->files);

        while (true) {
            sleep($this->interval);
            foreach ($this->files as $key => $file) {
                if (file_exists($file) && filemtime($file) !== $lastModified[$key]) {
                    $lastModified[$key] = filemtime($file);
                    // Notify changes or trigger auto-refresh
                    echo "File changed: $file\n";
                }
            }
        }
    }
}