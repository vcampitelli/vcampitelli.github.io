<?php
namespace Daemon\Pthreads;

use Daemon\DaemonManagerAbstract;

class DaemonManager extends DaemonManagerAbstract
{
    protected function startDaemon($class)
    {
        $task = new class extends Thread {
            private $response;

            public function run()
            {
                $content = file_get_contents("http://google.com");
                preg_match("~<title>(.+)</title>~", $content, $matches);
                $this->response = $matches[1];
            }
        };

        $task->start() && $task->join();
    }

    protected function stopDaemon($daemon)
    {
    }
}
