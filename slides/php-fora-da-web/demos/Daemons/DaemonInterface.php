<?php
namespace Daemon;

interface DaemonInterface
{
    public function shouldRun();

    public function run();
}
