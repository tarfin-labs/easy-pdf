<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;

class Split extends BasePdf
{
    /** @var string */
    protected $file;

    /** @var int */
    protected $chunkSize;

    /** @var array */
    protected $splittedFileContents;

    /**
     * Split constructor.
     *
     * @param string $file
     * @param int $chunkSize
     */
    public function __construct(string $file, int $chunkSize)
    {
        parent::__construct();

        $this->file = $file;
        $this->chunkSize = $chunkSize;
    }

    /**
     * Split pdf file into smaller pdf files.
     *
     * @return $this
     */
    public function render()
    {
        $this->setFileContent($this->file);

        $fileCount = EasyPdf::parser($this->content)->count();

        $chunks = array_chunk(range(1, $fileCount), $this->chunkSize);

        $streamReader = StreamReader::createByString($this->content);

        foreach ($chunks as $chunk) {
            $this->pdf = new Fpdi();
            $this->pdf->setSourceFile($streamReader);

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
