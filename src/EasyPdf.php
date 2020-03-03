<?php

namespace TarfinLabs\EasyPdf;

use Spatie\PdfToImage\Pdf;

class EasyPdf
{
    /**
     * Create a new Parser instance.
     *
     * @param string $path
     * @return Parser
     */
    public static function parser(string $path): Parser
    {
        return new Parser($path);
    }

    /**
     * Create a new Qr instance.
     *
     * @param string $path
     * @return Qr
     */
    public static function qr(string $path): Qr
    {
       return new Qr($path);
    }
}
