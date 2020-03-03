<?php

namespace TarfinLabs\EasyPdf;

use Zxing\QrReader;

class Qr
{
    protected $path;

    /**
     * Create a new Qr parser with image path file.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Scan the qr-code on the image and return the text.
     *
     * @return string
     */
    public function scan(): string
    {
        $qrcode = new QrReader($this->path);

        return $qrcode->text();
    }
}
