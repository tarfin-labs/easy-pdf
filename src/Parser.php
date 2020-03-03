<?php

namespace TarfinLabs\EasyPdf;

use Imagick;
use setasign\Fpdi\Tcpdf\Fpdi;
use Spatie\PdfToImage\Pdf;

class Parser
{
    protected $path;
    protected $pdf;
    protected $page;

    /**
     * Create a new Parser instance with pdf file path.
     *
     * Parser constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Return pdf page count.
     *
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     */
    public function count(): int
    {
        $pdf = new Fpdi();
        return $pdf->setSourceFile($this->path);
    }

    /**
     * Set page for save methods.
     *
     * @param int $page
     * @return Parser
     */
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Save pdf with given destination.
     *
     * @param string $filename
     * @param string $destination
     * @return mixed
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function savePdf(string $filename, string $destination)
    {
        $this->pdf = new Fpdi();
        $this->pdf->AddPage();
        $this->pdf->setSourceFile($this->path);
        $this->pdf->useTemplate($this->pdf->importPage($this->page));

        $this->pdf->Output($filename, $destination);

        return $this;
    }

    /**
     * Save pdf as image.
     *
     * @param string $filename
     * @param string|null $format
     * @return Parser
     * @throws \ImagickException
     */
    public function saveImage(string $filename, string $format=null)
    {
        $imagick = new Imagick();
        $imagick->readImage($this->path);
        $imagick->writeImage($filename);

        return $this;

    }
}
