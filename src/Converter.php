<?php

namespace TarfinLabs\EasyPdf;

use Imagick;

class Converter
{
    /**
     * Path where read to pdf.
     *
     * @var string
     */
    protected $path;

    /**
     * Imagick instance.
     *
     * @var Imagick
     */
    protected $imagick;

    /**
     * Converter constructor.
     * @param string $path
     * @throws \ImagickException
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->imagick = new Imagick();
        $this->imagick->readImage($this->path);
    }

    /**
     * @param string $filename
     * @return bool
     * @throws \ImagickException
     */
    public function toImage(string $filename)
    {
        return $this->imagick->writeImage($filename);
    }
}
