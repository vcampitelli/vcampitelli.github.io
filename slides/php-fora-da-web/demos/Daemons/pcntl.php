<?php
// @FIXME
include __DIR__ . '/DaemonManagerAbstract.php';
include __DIR__ . '/DaemonInterface.php';

include __DIR__ . '/Pcntl/DaemonManager.php';
$manager = new Daemon\Pcntl\DaemonManager();
$manager->start();
