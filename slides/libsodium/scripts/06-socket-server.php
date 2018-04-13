<?php
// Lê do stdin
$port = (empty($argv[1])) ? 0 : (int) $argv[1];
if ($port < 1) {
    $port = 8000;
}

require __DIR__ . '/src/SocketServer.php';
require __DIR__ . '/src/CryptSocketServer.php';
require __DIR__ . '/src/SimpleCrypt.php';
$server = new Vcampitelli\CryptSocketServer($port);
$server->start();
