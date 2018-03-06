<?php
namespace Daemon\Pthreads;

use Thread;
use Pool;

class Pthreads
{
    const BLOCKSIZE = 60;

    public function start($filename)
    {
        // @FIXME
        include_once __DIR__ . '/Example/Task.php';

        $this->pool = [];

        $threadsPerPool = 3;
        $poolSize = 3;

        echo "Iniciando {$poolSize} pools com {$threadsPerPool} threads cada" . PHP_EOL;

        /* Create a blank image */
        $image  = imagecreatetruecolor(
            self::BLOCKSIZE * $poolSize,
            self::BLOCKSIZE * $poolSize
        );

        imagefill($image, 0, 0, imagecolorallocate($image, 14, 14, 14));

        // Cria workers que serão executados simultaneamente
        $pool = new Pool($poolSize);

        // Submete tarefas para o pool
        for ($i = 1; $i <= $poolSize; ++$i) {
            for ($j = 1; $j <= $threadsPerPool; ++$j) {
                echo 'Iniciando ' . (($i - 1) * $threadsPerPool + $j) . 'a task...' . PHP_EOL;
                $pool->submit(
                    $this->buildTask(
                        new Example\Task($i * $j),
                        $image,
                        $filename,
                        ($i - 1) * self::BLOCKSIZE, // $startX
                        ($j - 1) * self::BLOCKSIZE, // $startY,
                        self::BLOCKSIZE
                    )
                );
            }
        }

        // Aguarda o término das tarefas
        while ($pool->collect());

        // Desliga todos os workers
        $pool->shutdown();

        echo "Todas as threads finalizadas." . PHP_EOL;

        imagepng($image, $filename, 0);
        imagedestroy($image);
    }

    protected function buildTask(Example\Task $task, $image, $filename, $startX, $startY, $blockSize)
    {
        return new class($task, $image, $filename, $startX, $startY, $blockSize) extends Thread {
            private $task;
            private $image;
            private $filename;
            private $startX;
            private $startY;
            private $blockSize;

            public function __construct(Example\Task $task, $image, $filename, $startX, $startY, $blockSize)
            {
                $this->task = $task;
                $this->image = $image;
                $this->filename = $filename;
                $this->startX = $startX;
                $this->startY = $startY;
                $this->blockSize = $blockSize;
            }

            public function run()
            {
                $this->task->run(
                    $this->image,
                    $this->filename,
                    $this->startX,
                    $this->startY,
                    $this->blockSize, // $width
                    10 // $steps
                );
            }
        };
    }
}
