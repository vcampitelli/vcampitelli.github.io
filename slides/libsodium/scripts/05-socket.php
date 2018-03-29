<?php
// Lê do stdin
$port = (empty($argv[1])) ? 0 : (int) $argv[1];
if ($port < 1) {
    $port = 8000;
}

require __DIR__ . '/src/SocketServer.php';
$server = new Vcampitelli\SocketServer($port);
$server->start();
