<?php
namespace Daemon\Pthreads;

use Thread;
use Pool;

class Pthreads
{
    public function start($coloredImage, $destinationImage)
    {
        // @FIXME
        include_once __DIR__ . '/Example/Task.php';

        $this->pool = [];

        echo 'Iniciando pool com 4 threads' . PHP_EOL;

        // Imagem colorida
        $imageResource = imagecreatefrompng($coloredImage);
        $width = imagesx($imageResource);
        $height = imagesy($imageResource);

        // Imagem em cinza
        $blankResource = imagecreatetruecolor($width, $height);
        imagefilledrectangle($blankResource, 0, 0, $width, $height, imagecolorallocate($blankResource, 14, 14, 14));

        $pool = [];

        // Cria um pool com 4 workers que serão executados simultaneamente
        $pool = new Pool(4);

        // Submete tarefas para o pool
        $halfWidth = $width / 2;
        $halfHeight = $height / 2;
        $poolCoords = [
            [0,                 0,                  ceil($halfWidth),   ceil($halfHeight),  Example\Task::DIRECTION_1],
            [floor($halfWidth), 0,                  $width,             ceil($halfHeight),  Example\Task::DIRECTION_2],
            [0,                 floor($halfHeight), ceil($halfWidth),   $height,            Example\Task::DIRECTION_3],
            [floor($halfWidth), floor($halfHeight), $width,             $height,            Example\Task::DIRECTION_4]
        ];

        foreach ($poolCoords as $i => $coords) {
            echo "Iniciando task #{$i}..." . PHP_EOL;
            $pool->submit(
                new Example\Task(
                    $i,
                    $imageResource,
                    $blankResource,
                    $destinationImage,
                    $coords[0],
                    $coords[1],
                    $coords[2],
                    $coords[3],
                    $coords[4]
                )
            );
        }

        // Aguarda o término das tarefas
        while ($pool->collect());

        // Desliga todos os workers
        $pool->shutdown();

        echo "Todas as threads finalizadas." . PHP_EOL;

        imagepng($blankResource, $destinationImage, 0);
        imagedestroy($imageResource);
        imagedestroy($blankResource);
    }
}
