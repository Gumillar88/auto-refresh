<?php

namespace Glw\AutoRefresh;

class FileWatcher
{
    private $watchedFiles;

    public function __construct(array $watchedFiles)
    {
        $this->watchedFiles = $watchedFiles;
    }

    public function watch()
    {
        // Implement simple file watching using polling
        $lastModifiedTimes = [];

        while (true) {
            foreach ($this->watchedFiles as $directory) {
                $files = glob("$directory/*");
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $lastModified = filemtime($file);
                        if (!isset($lastModifiedTimes[$file])) {
                            $lastModifiedTimes[$file] = $lastModified;
                        } elseif ($lastModifiedTimes[$file] != $lastModified) {
                            $lastModifiedTimes[$file] = $lastModified;
                            return true; // File changed
                        }
                    }
                }
            }
            sleep(1); // Polling interval
        }
    }
}