<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;

class Merge extends BasePdf
{
    /**
     * @var array
     */
    protected $files;

    /**
     * Merge constructor.
     *
     * @param array $files
     */
    public function __construct(array $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Merge pdf files into one pdf.
     *
     * @return $this
     *
     * @throws Exceptions\UnableToOpen
     */
    public function render()
    {
        foreach ($this->files as $file) {
            $this->setFileContent($file);

            $count = $this->pdf->setSourceFile(StreamReader::createByString($this->content));

            for ($page = 1; $page <= $count; $page++) {
                $this->pdf->AddPage();
                $importedPage = $this->pdf->ImportPage($page);
                $this->pdf->useTemplate($importedPage);
            }
        }

        return $this;
    }
}
