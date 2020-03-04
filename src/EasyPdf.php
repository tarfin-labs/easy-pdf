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
     * EasyPdf constructor.
     */
    public function __construct()
    {
        $this->pdf = new TCPDF();
        $this->pdf->setPrintHeader($this->header);
        $this->pdf->setPrintFooter($this->footer);
        $this->pdf->AddPage();
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
     * @return EasyPdf
     */
    public function withFont(string $font)
    {
        $tcpdfFont = TCPDF_FONTS::addTTFfont($font);

        $this->pdf->AddFont($tcpdfFont);

        return $this;
    }

    /**
     * Add custom fonts on the pdf instance.
     *
     * @param array $fonts
     * @return EasyPdf
     */
    public function withFonts(array $fonts)
    {
        foreach ($fonts as $font) {
            $this->withFont($font);
        }

        return $this;
    }

    /**
     * Set given font as default font.
     *
     * @param string $font
     * @return $this
     */
    public function setFont(string $font)
    {
        $tcpdfFont = TCPDF_FONTS::addTTFfont($font);

        $this->pdf->SetFont($tcpdfFont);

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
        $this->pdf->writeHTML($html);

        return $this;
    }

    public function setHeader($header = null)
    {
        if (!is_null($header)) {
            $this->header = $header;
        }

        $this->pdf->setPrintHeader($this->header);
    }

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
     * @param string $filename
     * @return $this
     */
    public function stream(string $filename)
    {
        $this->pdf->Output($filename, 'I');

        return $this;
    }

    /**
     * Return pdf content as a string.
     *
     * @param string $filename
     * @return string
     */
    public function content(string $filename)
    {
        return $this->pdf->Output($filename, 'S');
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
