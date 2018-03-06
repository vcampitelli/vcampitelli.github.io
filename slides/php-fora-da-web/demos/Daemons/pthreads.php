<?php
// @FIXME
include __DIR__ . '/Pthreads/Pthreads.php';

$manager = new Daemon\Pthreads\Pthreads();
$manager->start(__DIR__ . '/threads.png');
