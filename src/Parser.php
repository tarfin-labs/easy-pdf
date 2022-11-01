<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;

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
     * Returns splitted pdf file contents.
     *
     * @param  int  $chunkSize
     * @return array
     *
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function splitTo(int $chunkSize)
    {
        $chunks = array_chunk(range(1, $this->count()), $chunkSize);

        $streamReader = StreamReader::createByString($this->content);

        $splittedFileContents = [];

        foreach ($chunks as $chunk) {
            $this->pdf = new Fpdi();
            $this->pdf->setSourceFile($streamReader);

            foreach ($chunk as $index) {
                $this->pdf->AddPage();
                $this->pdf->useTemplate($this->pdf->importPage($index));
            }

            $splittedFileContents[] = $this->pdf->Output('doc.pdf', 'S');
        }

        return $splittedFileContents;
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
