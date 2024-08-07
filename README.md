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

Add the following JavaScript code to your HTML file to connect to the WebSocket server:

<script>
    let socket = new WebSocket("ws://localhost:8080");

    socket.onmessage = function(event) {
        if (event.data === 'reload') {
            location.reload(); // Auto-refresh the browser
        }
    };
</script>

Run the Server

Start the PHP WebSocket server with the following command:

php examples/example.php
