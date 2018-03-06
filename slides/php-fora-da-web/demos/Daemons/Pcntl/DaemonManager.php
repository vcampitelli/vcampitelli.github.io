<?php
namespace Daemon\Pcntl;

use Daemon\DaemonManagerAbstract;

class DaemonManager extends DaemonManagerAbstract
{
    protected function startDaemon($class, $id)
    {
        $pid = pcntl_fork();
        if ($pid == -1) {
            throw new RuntimeException("Houve um erro ao fazer o fork para a robô {$class}");
        }

        if ($pid) {
            // Processo pai
            return $pid;
        }

        // Processo filho (robô)
        $daemon = new $class();
        while ($daemon->shouldRun()) {
            $daemon->run();
            pcntl_signal_dispatch();
            sleep(rand(1, 3));
        }
        die();
    }

    protected function stopDaemon($daemon)
    {
    }

    protected function watch()
    {
        $count = count($this->pool);
        while ($count > 0) {
            foreach ($this->pool as $index => $pid) {
                $return = pcntl_waitpid($pid, $status, WNOHANG);

                // Segundo o manual do pcntl_waitpid() [http://php.net/pcntl_waitpid]:
                // returns the process ID of the child which exited, -1 on error or zero
                // if WNOHANG was used and no child was available
                if ($return) {
                    unset($this->pool[$index]);
                    echo "Filho {$pid} morreu..." . PHP_EOL;
                    --$count;
                }
            }

            sleep(1);
        }
    }

    // Busque os robôs que devem ser executados de
    // um banco de dados, arquivo de configuração, etc
    protected function getDaemons()
    {
        // @FIXME
        include_once __DIR__ . '/Example/DaemonAbstract.php';
        include_once __DIR__ . '/Example/Daemon1.php';
        include_once __DIR__ . '/Example/Daemon2.php';

        return [
            Example\Daemon1::class,
            Example\Daemon2::class
        ];
    }
}
