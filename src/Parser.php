<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\PdfParser\StreamReader;

class Parser extends BasePdf
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var int
     */
    protected $page;

    /**
     * Parser constructor.
     *
     * @param  string  $path
     *
     * @throws Exceptions\UnableToOpen
     */
    public function __construct(string $path)
    {
        parent::__construct();

        $this->path = $path;
        $this->setFileContent($this->path);
    }

    /**
     * Return pdf page count.
     *
     * @return int
     *
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     */
    public function count(): int
    {
        return $this->pdf->setSourceFile(StreamReader::createByString($this->content));
    }

    /**
     * Set page for save methods.
     *
     * @param  int  $page
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
     */
    public function render()
    {
        $this->pdf->AddPage();

        $this->pdf->setSourceFile(StreamReader::createByString($this->content));

        $this->pdf->useTemplate($this->pdf->importPage($this->page));

        return $this;
    }
}
