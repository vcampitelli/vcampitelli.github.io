<?php
namespace Daemon\Pcntl\Example;

class Daemon1 extends DaemonAbstract
{
    protected $color = "\033[0;33m";

    public function shouldRun()
    {
        return ++$this->index < 15;
    }
}
