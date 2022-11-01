<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;

class Split extends BasePdf
{
    /** @var string */
    protected $fileContent;

    /** @var int */
    protected $chunkSize;

    /** @var array */
    protected $splittedFileContents;

    /**
     * Split constructor.
     *
     * @param string $fileContent
     * @param int $chunkSize
     */
    public function __construct(string $fileContent, int $chunkSize)
    {
        parent::__construct();

        $this->fileContent = $fileContent;
        $this->chunkSize = $chunkSize;
    }

    /**
     * Split pdf file into smaller pdf files.
     *
     * @return $this
     */
    public function render()
    {
        $fileCount = EasyPdf::parser($this->fileContent)->count();

        $chunks = array_chunk(range(1, $fileCount), $this->chunkSize);

        foreach ($chunks as $chunk) {
            $this->pdf = new Fpdi();
            $this->pdf->setSourceFile(StreamReader::createByString($this->fileContent));

            foreach ($chunk as $index) {
                $this->pdf->AddPage();
                $this->pdf->useTemplate($this->pdf->importPage($index));
            }

            $this->splittedFileContents[] = $this->pdf->Output('doc.pdf', 'S');
        }

        return $this;
    }

    /**
     * Returns splitted pdf file contents
     *
     * @return array
     */
    public function splittedFileContents()
    {
        return $this->splittedFileContents;
    }
}
