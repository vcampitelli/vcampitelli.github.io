<?php
namespace Daemon\Pthreads\Example;

class Task
{
    protected $id = 0;

    public function __construct($id)
    {
        $this->id = $id;
        $colors = [
            "\033[0;31m",
            "\033[0;32m",
            "\033[0;33m",
            "\033[0;34m",
            "\033[0;35m",
            "\033[0;36m",
            "\033[0;37m",
            "\033[0;38m",
            "\033[0;39m",
            "\033[0;40m"
        ];
        $this->color = $colors[$id % count($colors)];
    }

    public function run($image, $filename, $startX, $startY, $width, $steps)
    {
        $color = imagecolorallocate($image, random_int(0, 255), random_int(0, 255), random_int(0, 255));

        for ($x = $startX; $x < $startX + $width - 1; $x += $steps) {
            echo str_repeat("\t", $this->id) . "{$this->color}[{$this->id}] Rodando...\033[m" . PHP_EOL;

            for ($y = $startY; $y < $startY + $width - 1; $y += $steps) {
                /*
                echo str_repeat("\t", $this->id) . "{$this->color}[{$this->id}] " .
                    "{$x} x {$y} -> " . ($x + $width) . " x " . ($y + $steps) .
                    "\033[m" . PHP_EOL;
                */
                imagefilledrectangle($image, $x, $y, $x + $steps, $y + $steps, $color);
                imagepng($image, $filename, 0);
                usleep(random_int(10000, 500000));
            }
            usleep(random_int(10000, 500000));
        }
        echo str_repeat("\t", $this->id) . "{$this->color}[{$this->id}] Finalizando...\033[m" . PHP_EOL;
    }
}
