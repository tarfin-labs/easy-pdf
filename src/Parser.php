<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\Tcpdf\Fpdi;

class Parser
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var Fpdi
     */
    protected $pdf;

    /**
     * @var int
     */
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
        $this->pdf = new Fpdi();
    }

    /**
     * Return pdf page count.
     *
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     */
    public function count(): int
    {
        return $this->pdf->setSourceFile($this->path);
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
     * Render given source.
     *
     * @return Parser
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function render()
    {
        $this->pdf->AddPage();
        $this->pdf->setSourceFile($this->path);
        $this->pdf->useTemplate($this->pdf->importPage($this->page));

        return $this;
    }

    /**
     * Save to a local server file with the name given by name.
     *
     * @param string $filename
     * @return string
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function save(string $filename)
    {
        $this->render();

        return $this->pdf->Output($filename, 'F');
    }

    /**
     * Send the Pdf inline to the browser.
     *
     * @param string $filename
     * @return string
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function stream(string $filename)
    {
        $this->render();

        return $this->pdf->Output($filename, 'I');
    }

    /**
     * Return the pdf as a string.
     *
     * @param $filename
     * @return string
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function content($filename)
    {
        $this->render();

        return $this->pdf->Output($filename, 'S');
    }
}
