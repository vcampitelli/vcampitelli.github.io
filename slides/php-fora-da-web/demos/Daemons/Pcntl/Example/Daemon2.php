<?php
namespace Daemon\Pcntl\Example;

class Daemon2 extends DaemonAbstract
{
    protected $color = "\033[0;36m";

    public function shouldRun()
    {
        return ++$this->index < 20;
    }
}
