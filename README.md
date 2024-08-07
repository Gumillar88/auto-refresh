# Auto-Refresh PHP Library

## Description

Auto-Refresh is a PHP library that enables automatic browser refresh using WebSocket whenever there is a change in the monitored PHP files. This is especially useful during development when you want to see changes live without manual refreshing.

## Features

- Monitors changes in PHP files and directories
- Sends signals to the browser for auto-refresh
- Easy to use with simple configuration

## Installation

You can install this library using Composer by running the following command:

```bash
composer require glw/auto-refresh
```
Add the following JavaScript code to your HTML file to connect to the WebSocket server:
```js
<script>
    let socket = new WebSocket("ws://localhost:8080");

    socket.onmessage = function(event) {
        if (event.data === 'reload') {
            location.reload(); // Auto-refresh the browser
        }
    };
</script>
```
Run the Server

Start the PHP WebSocket server with the following command:

```bash
php examples/example.php
```
To use the glw/auto-refresh library in Laravel, follow these steps:

1. Install the Library
Install the glw/auto-refresh library using Composer:

```bash
composer require glw/auto-refresh
```

2. Add WebSocket Server to Laravel
Create an Artisan command to run the WebSocket server that monitors file changes:

a. Create Artisan Command
Run the following command to create an Artisan command:

```bash
php artisan make:command AutoRefreshServer
```
b. Edit Artisan Command
Open the newly created command file at app/Console/Commands/AutoRefreshServer.php and edit it as follows:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Glw\AutoRefresh\AutoRefreshServer;

class AutoRefreshServer extends Command
{
    protected $signature = 'auto-refresh:serve';
    protected $description = 'Start the Auto-Refresh WebSocket server';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $watchedFiles = [
            base_path('app'),       // Monitor app directory
            base_path('resources'), // Monitor resources directory
            base_path('routes'),    // Monitor routes directory
            // Add other directories you want to monitor
        ];

        AutoRefreshServer::runServer($watchedFiles);
    }
}
```

3. Add JavaScript Client
Add the following JavaScript code to your Blade file (e.g., resources/views/welcome.blade.php) to connect to the WebSocket server:

```js
<script>
    let socket = new WebSocket("ws://localhost:8080");

    socket.onmessage = function(event) {
        if (event.data === 'reload') {
            location.reload(); // Auto-refresh the browser
        }
    };
</script>
```

4. Run the WebSocket Server
Run the newly created Artisan command to start the WebSocket server:

```bash
php artisan auto-refresh:serve
```

The WebSocket server will now monitor changes in the directories you specified and send a signal to the browser to auto-refresh if there are any changes. Make sure to run this command in the terminal while developing your Laravel application.