<?php

namespace TarfinLabs\EasyPdf;

use finfo;
use setasign\Fpdi\Tcpdf\Fpdi;
use TarfinLabs\EasyPdf\Exceptions\UnableToOpen;

abstract class BasePdf
{
    /**
     * @var Fpdi
     */
    protected $pdf;

    /**
     * @var string
     */
    protected $content;

    /**
     * BasePdf constructor.
     */
    public function __construct()
    {
        $this->pdf = new Fpdi();
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
    }

    /**
     * Check for url given path.
     *
     * @param $url
     * @return bool
     */
    protected function validUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        return false;
    }

    /**
     * Check file type from string content is pdf.
     *
     * @param $content
     * @return string
     */
    protected function mimeType($content)
    {
        $finfo = new finfo(1040);

        $buffer = $finfo->buffer($content);

        if ($buffer === 'application/pdf; charset=binary') {
            return  true;
        }

        return false;
    }

    /**
     * Set file content.
     *
     * @param $pdf
     *
     * @throws UnableToOpen
     */
    protected function setFileContent($pdf)
    {
        if ($this->validUrl($pdf)) {
            $this->content = file_get_contents($pdf);
        } elseif ($this->mimeType($pdf)) {
            $this->content = $pdf;
        } elseif (file_exists($pdf)) {
            $this->content = file_get_contents($pdf);
        } else {
            throw UnableToOpen::noSuchFile($pdf);
        }
    }

    /**
     * Render pdf content.
     *
     * @return mixed
     */
    abstract public function render();

    /**
     * Save to a local server file with the name given by name.
     *
     * @param  string  $filename
     * @return string
     */
    public function save(string $filename)
    {
        $this->render();

        return $this->pdf->Output($filename, 'F');
    }

    /**
     * Send the Pdf inline to the browser.
     *
     * @return string
     */
    public function stream()
    {
        $this->render();

        return $this->pdf->Output('doc.pdf', 'I');
    }

    /**
     * Return the pdf or pdfs as a string.
     *
     * @return string|array
     */
    public function content()
    {
        $this->render();

        return $this->pdf->Output('doc.pdf', 'S');
    }
}
