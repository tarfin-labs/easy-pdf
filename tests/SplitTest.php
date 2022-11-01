<?php

namespace TarfinLabs\EasyPdf\Tests;

use finfo;
use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class SplitTest extends TestCase
{
    protected $file;

    protected function setUp(): void
    {
        parent::setUp();

        $this->file = __DIR__.'/files/multiple.pdf';
    }

    /**
     * @test
     *
     * @see \TarfinLabs\EasyPdf\Split::render()
     */
    public function it_can_split_one_file_to_multiple_files()
    {
        $pdfs = EasyPdf::split($this->file, 1)
            ->content();

        foreach ($pdfs as $pdf) {
            $finfo = new finfo(FILEINFO_MIME);
            $buffer = $finfo->buffer($pdf);
            $this->assertSame('application/pdf; charset=binary', $buffer);
        }

        $this->assertCount(3, $pdfs);
    }
}
