<?php
namespace Daemon;

abstract class DaemonManagerAbstract
{
    protected $pool = [];

    public function start()
    {
        $this->pool = [];
        $i = 0;

        foreach ($this->getDaemons() as $class) {
            $pid = $this->startDaemon($class, ++$i);
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

    abstract protected function startDaemon($class, $id);

    abstract protected function stopDaemon($daemon);

    abstract protected function watch();
}
