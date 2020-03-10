<?php

namespace TarfinLabs\EasyPdf;

use TCPDF;
use TCPDF_FONTS;

class EasyPdf
{
    /**
     * Tcpdf instance.
     *
     * @var TCPDF
     */
    protected $pdf;

    /**
     * Pdf header data.
     *
     * @var bool
     */
    protected $header = false;

    /**
     * Pdf footer data.
     *
     * @var bool
     */
    protected $footer = false;

    /**
     * Barcode style.
     *
     * @var array
     */
    private $style = [
        'border' => false,
        'padding' => 0,
        'fgcolor' => [0, 0, 0],
        'bgcolor' => false,
    ];

    /**
     * EasyPdf constructor.
     */
    public function __construct()
    {
        $this->pdf = new TCPDF();
        $this->pdf->setPrintHeader($this->header);
        $this->pdf->setPrintFooter($this->footer);
    }

    /**
     * Add an array of information to the pdf instance.
     *
     * @param array $information
     * @return EasyPdf
     */
    public function withInformation(array $information)
    {
        foreach ($information as $key => $value) {
            $this->setInformation($key, $value);
        }

        return $this;
    }

    /**
     * Set an information on the pdf instance.
     *
     * @param $information
     * @param $value
     * @return EasyPdf
     */
    protected function setInformation($information, $value)
    {
        $fn = 'Set' . $information;

        if (is_array($value)) {
            call_user_func([$this->pdf, $fn], ...$value);
        } else {
            call_user_func([$this->pdf, $fn], $value);
        }

        return $this;
    }

    /**
     * Add an array of configurations on the pdf instance.
     *
     * @param array $config
     * @return $this
     */
    public function withConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $this->setConfig($key, $value);
        }

        return $this;
    }

    /**
     * Set a configuration on the pdf instance.
     *
     * @param $config
     * @param $value
     * @return $this
     */
    protected function setConfig($config, $value)
    {
        $fn = 'set' . $config;

        if (is_array($value)) {
            call_user_func([$this->pdf, $fn], ...$value);
        } else {
            call_user_func([$this->pdf, $fn], $value);
        }

        return $this;
    }

    /**
     * Add custom font on the pdf instance.
     *
     * @param string $font
     * @param int|null $size
     * @return EasyPdf
     */
    public function addFont(string $font, int $size=null)
    {
        $tcpdfFont = TCPDF_FONTS::addTTFfont($font);

        $this->pdf->SetFont($tcpdfFont, '', $size);

        return $this;
    }

    /**
     * Set given font as default font.
     *
     * @param string $font
     * @param int|null $size
     * @return $this
     */
    public function setFont(string $font, int $size=null)
    {
        $this->pdf->SetFont($font, '', $size);

        return $this;
    }

    /**
     * Write html content.
     *
     * @param $html
     * @return EasyPdf
     */
    public function loadHtml($html)
    {
        $this->pdf->AddPage();
        $this->pdf->writeHTML($html);

        return $this;
    }

    /**
     * Add image to the pdf with given position and size.
     *
     * @param $image
     * @param $x
     * @param $y
     * @param $width
     * @param $height
     * @return EasyPdf
     */
    public function addImage($image, $x, $y, $width, $height)
    {
        $this->pdf->Image($image, $x, $y, $width, $height);

        return $this;
    }

    /**
     * Override default style for barcode.
     *
     * @param array $style
     * @return $this
     */
    public function setBarcodeStyle(array $style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Add qrcode with best error correction to the pdf with given position and size.
     *
     * @param $text
     * @param $x
     * @param $y
     * @param $width
     * @param $height
     * @param string $position
     * @return EasyPdf
     */
    public function addQrcode($text, $x, $y, $width, $height, $position = 'N')
    {
        $this->pdf->write2DBarcode($text, 'QRCODE,H', $x, $y, $width, $height, $this->style, $position);

        return $this;
    }

    /**
     * Add RAW2 barcode to the pdf with given position and size.
     *
     * @param $code
     * @param $x
     * @param $y
     * @param $width
     * @param $height
     * @param string $position
     * @return EasyPdf
     */
    public function addBarcode($code, $x, $y, $width, $height, $position = 'N')
    {
        $this->pdf->write2DBarcode($code, 'RAW2', $x, $y, $width, $height, $this->style, $position);

        return $this;
    }

    /**
     * Set Tcpdf print header.
     *
     * @param null $header
     */
    public function setHeader($header = null)
    {
        if (!is_null($header)) {
            $this->header = $header;
        }

        $this->pdf->setPrintHeader($this->header);
    }

    /**
     * Set Tcpdf print footer.
     *
     * @param null $footer
     */
    public function setFooter($footer = null)
    {
        if (!is_null($footer)) {
            $this->footer = $footer;
        }

        $this->pdf->setPrintFooter($this->footer);
    }

    /**
     * Save pdf to given path..
     *
     * @param string $filename
     * @return mixed
     */
    public function save(string $filename)
    {
        $this->pdf->Output($filename, 'F');

        return $this;
    }

    /**
     * Output the generated PDF to browser.
     *
     * @return $this
     */
    public function stream()
    {
        $this->pdf->Output('doc.pdf', 'I');

        return $this;
    }

    /**
     * Return pdf as a string.
     *
     * @return string
     */
    public function content()
    {
        return $this->pdf->Output('doc.pdf', 'S');
    }

    /**
     * Create a new Merge instance.
     *
     * @param array $files
     * @return Merge
     */
    public static function merge(array $files)
    {
        return new Merge($files);
    }

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
}
