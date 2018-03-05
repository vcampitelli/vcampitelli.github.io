<?php
namespace Daemon\Pthreads\Example;

use Daemon\DaemonInterface;

class Task2 implements DaemonInterface
{
    protected $index = 0;

    public function shouldRun()
    {
        return ++$this->index < 10;
    }

    public function run()
    {
        echo "\033[0;36m[Task2] Rodando {$this->index}\033[m" . PHP_EOL;
    }
}
