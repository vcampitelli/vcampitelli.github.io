<?php
namespace Daemon;

abstract class DaemonManagerAbstract
{
    protected $pool = [];

    public function start()
    {
        $this->pool = [];

        foreach ($this->getDaemons() as $class) {
            $pid = $this->startDaemon($class);
            echo "Iniciando {$class} ({$pid})..." . PHP_EOL;
            $this->pool[$class] = $pid;
        }

        $this->watch();
    }

    public function stop()
    {
        foreach ($this->pool as $daemon) {
            $this->stopDaemon($daemon);
        }
    }

    abstract protected function getDaemons();

    abstract protected function startDaemon($class);

    abstract protected function stopDaemon($daemon);

    abstract protected function watch();
}
