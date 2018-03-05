<?php
namespace Daemon\Pcntl\Example;

use Daemon\DaemonInterface;

class Daemon1 implements DaemonInterface
{
    protected $index = 0;

    public function shouldRun()
    {
        return ++$this->index < 10;
    }

    public function run()
    {
        echo "\033[0;33m[Daemon1] Rodando {$this->index}\033[m" . PHP_EOL;
    }
}
