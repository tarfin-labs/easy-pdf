<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\Tcpdf\Fpdi;

class Merge
{
    /**
     * @var array
     */
    protected $files;

    /**
     * @var Fpdi
     */
    protected $pdf;

    /**
     * Merge constructor.
     *
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;

        $this->pdf = new Fpdi();
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
    }

    /**
     * Merge pdf files into one pdf.
     *
     * @return $this
     */
    public function render()
    {
        foreach ($this->files as $file) {
            $count = $this->pdf->setSourceFile($file);

            for ($page = 1; $page <= $count; $page++) {
                $this->pdf->AddPage();
                $importedPage = $this->pdf->ImportPage($page);
                $this->pdf->useTemplate($importedPage);
            }
        }

        return $this;
    }

    /**
     * Save to a local server file with the name given by name.
     *
     * @param string $filename
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
     * Return the pdf as a string.
     *
     * @return string
     */
    public function content()
    {
        $this->render();

        return $this->pdf->Output('doc.pdf', 'S');
    }
}
