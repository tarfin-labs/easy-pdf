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
            $this->pdf->setPrintHeader(false);
            $this->pdf->setPrintFooter(false);

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
     * Splits the PDF into multiple files using a default chunk size while
     * preserving the specified page ranges as individual chunks.
     *
     * Exception ranges are never split, even if they exceed the default chunk size.
     *
     * @param  int  $defaultChunkSize  Default number of pages per split.
     * @param  array<int, array{0:int, 1:int}>  $exceptions  Page ranges to keep
     *         together, where each range is in the format [startPage, endPage].
     * @return array<string>
     *
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\Filter\FilterException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function splitWithExceptions(int $defaultChunkSize, array $exceptions = [])
    {
        $totalPages = $this->count();
        $streamReader = StreamReader::createByString($this->content);

        usort($exceptions, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        $splittedFileContents = [];
        $currentPage = 1;
        $exceptionIndex = 0;

        while ($currentPage <= $totalPages) {

            $nextException = $exceptions[$exceptionIndex] ?? null;

            if ($nextException) {

                [$exceptionStart, $exceptionEnd] = $nextException;

                if ($currentPage == $exceptionStart) {

                    $lastPage = min($exceptionEnd, $totalPages);

                    $exceptionIndex++;

                }elseif ($currentPage < $exceptionStart && ($currentPage + $defaultChunkSize - 1) >= $exceptionStart) {
                    $lastPage = $exceptionStart - 1;

                }else {
                    $lastPage = min($currentPage + $defaultChunkSize - 1, $totalPages);
                }
            }else {
                $lastPage = min($currentPage + $defaultChunkSize - 1, $totalPages);
            }

            $this->pdf = new Fpdi();
            $this->pdf->setPrintHeader(false);
            $this->pdf->setPrintFooter(false);

            $this->pdf->setSourceFile($streamReader);

            for ($page = $currentPage; $page <= $lastPage; $page++) {

                $templateId = $this->pdf->importPage($page, '/CropBox');

                $size = $this->pdf->getTemplateSize($templateId);

                $this->pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                $this->pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height'], true);
            }

            $splittedFileContents[] = $this->pdf->Output('doc.pdf', 'S');

            $currentPage = $lastPage + 1;
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
