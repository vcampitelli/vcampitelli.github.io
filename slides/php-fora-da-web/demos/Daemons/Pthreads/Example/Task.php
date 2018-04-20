<?php
namespace Daemon\Pthreads\Example;

class Task extends \Thread
{
    const DIRECTION_1 = 1;
    const DIRECTION_2 = 2;
    const DIRECTION_3 = 3;
    const DIRECTION_4 = 4;

    protected $id = 0;

    private $imageResource;
    private $blankResource;
    private $destinationImage;
    private $startX;
    private $endX;
    private $startY;
    private $endY;
    private $direction;

    public function __construct(
        $id,
        $imageResource,
        $blankResource,
        $destinationImage,
        $startX,
        $startY,
        $endX,
        $endY,
        $direction
    ) {
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
        $this->imageResource = $imageResource;
        $this->blankResource = $blankResource;
        $this->destinationImage = $destinationImage;
        $this->startX = (int) $startX;
        $this->endX = (int) $endX;
        $this->startY = (int) $startY;
        $this->endY = (int) $endY;
        $this->direction = $direction;
        $this->color = $colors[$id % count($colors)];
    }

    public function run()
    {
        $this->pool = [];
        $this->banner();

        switch ($this->direction) {
            case self::DIRECTION_1:
                for ($x = $this->startX; $x <= $this->endX; ++$x) {
                    for ($y = $this->startY; $y <= $this->endY; ++$y) {
                        $this->for($x, $y);
                    }
                    $this->finishY();
                }
                break;

            case self::DIRECTION_2:
                for ($x = $this->endX - 1; $x > $this->startX; --$x) {
                    for ($y = $this->startY; $y <= $this->endY; ++$y) {
                        $this->for($x, $y);
                    }
                    $this->finishY();
                }
                break;

            case self::DIRECTION_3:
                for ($x = $this->startX; $x <= $this->endX; ++$x) {
                    for ($y = $this->endY - 1; $y > $this->startY; --$y) {
                        $this->for($x, $y);
                    }
                    $this->finishY();
                }
                break;

            case self::DIRECTION_4:
                for ($x = $this->endX - 1; $x > $this->startX; --$x) {
                    for ($y = $this->endY - 1; $y > $this->startY; --$y) {
                        $this->for($x, $y);
                    }
                    $this->finishY();
                }
                break;
        }

        $this->finishAll();
    }

    protected function banner()
    {
        echo str_repeat("\t", $this->id) . "{$this->color}[{$this->id}] Rodando...\033[m" . PHP_EOL;
    }

    protected function finishY()
    {
        $resource = $this->blankResource;
        $filename = $this->destinationImage;
        $this->synchronized(function () use ($resource, $filename) {
            imagepng($resource, $filename, 0);
        });
        usleep(random_int(10000, 300000));
    }

    protected function finishAll()
    {
        echo str_repeat("\t", $this->id) . "{$this->color}[{$this->id}] Finalizando...\033[m" . PHP_EOL;
        $resource = $this->blankResource;
        $filename = $this->destinationImage;
        $this->synchronized(function () use ($resource, $filename) {
            imagepng($resource, $filename, 0);
        });
        usleep(random_int(10000, 200000));
    }

    protected function for($x, $y)
    {
        // echo "{$this->id}\t{$x}\t{$y}\n";
        $rgb = imagecolorsforindex($this->imageResource, imagecolorat($this->imageResource, $x, $y));

        $index = "{$rgb['red']}.{$rgb['green']}.{$rgb['blue']}";
        if (!isset($this->pool[$index])) {
            // http://php.net/manual/en/function.imagecolorallocate.php#94785
            $this->pool[$index] = imagecolorexact($this->blankResource, $rgb['red'], $rgb['green'], $rgb['blue']);
            if ($this->pool[$index] == -1) {
                $this->pool[$index] = (imagecolorstotal($this->blankResource) >= 255)
                    ? imagecolorclosest($this->blankResource, $rgb['red'], $rgb['green'], $rgb['blue'])
                    : imagecolorallocate($this->blankResource, $rgb['red'], $rgb['green'], $rgb['blue']);
            }
        }

        imagesetpixel($this->blankResource, $x, $y, $this->pool[$index]);
        usleep(random_int(100, 200));
    }
}
