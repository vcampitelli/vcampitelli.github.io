<?php
namespace Daemon\Pcntl\Example;

use Daemon\DaemonInterface;

abstract class DaemonAbstract implements DaemonInterface
{
    const BASH_COLOR_RESET = "\033[m";

    protected $index = 0;

    protected $color = '';

    protected $pid;

    public function __construct()
    {
        $this->pid = posix_getpid();

        pcntl_signal(SIGINT, [$this, 'signalHandler']);
        pcntl_signal(SIGTERM, [$this, 'signalHandler']);
        pcntl_signal(SIGHUP, [$this, 'signalHandler']);
        pcntl_signal(SIGUSR1, [$this, 'signalHandler']);
        pcntl_signal(SIGUSR2, [$this, 'signalHandler']);
    }

    public function run()
    {
        echo "{$this->color}" . date('H:i:s') . substr((string) microtime(), 1, 8). " [{$this->pid}] ";
        $this->doRun();
    }

    protected function doRun()
    {
        echo "Rodando ({$this->index})" . self::BASH_COLOR_RESET . PHP_EOL;
    }

    public function signalHandler($signal)
    {
        echo "\t{$this->color}{$this->pid} Capturado sinal {$signal}";
        switch ($signal) {
            case SIGTERM:
            case SIGINT:
            case SIGHUP:
                echo ' - terminando graciosamente...';
                echo self::BASH_COLOR_RESET . PHP_EOL;
                die();
                break;

            case SIGUSR1:
                echo ' - SIGUSR1';
                break;

            case SIGUSR2:
                echo ' - SIGUSR2';
                break;
        }

        echo self::BASH_COLOR_RESET . PHP_EOL;
    }
}
