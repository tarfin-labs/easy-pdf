<?php

namespace TarfinLabs\EasyPdf;

use setasign\Fpdi\PdfParser\StreamReader;

class Merge extends BasePdf
{
    /**
     * @var array
     */
    protected $files;

    protected $watermark;
    protected $w_x;
    protected $w_y;
    protected $w_width;
    protected $w_height;

    /**
     * Merge constructor.
     *
     * @param  array  $files
     */
    public function __construct(array $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Add watermark to each page.
     *
     * @param  $watermark
     * @param  $x
     * @param  $y
     * @param  $width
     * @param  $height
     * @return $this
     */
    public function addWatermark($watermark, $x = null, $y = null, $width = 0, $height = 0)
    {
        $this->watermark = $watermark;
        $this->w_x = $x;
        $this->w_y = $y;
        $this->w_width = $width;
        $this->w_height = $height;

        return $this;
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
                if ($this->watermark) {
                    $this->pdf->Image($this->watermark, $this->w_x, $this->w_y, $this->w_width, $this->w_height);
                }
            }
        }

        return $this;
    }
}
