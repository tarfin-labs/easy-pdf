<?php

namespace TarfinLabs\EasyPdf\Tests;

use finfo;
use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class MergeTest extends TestCase
{
    protected $file;
    protected $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->file = __DIR__.'/files/file.pdf';

        $this->files = [
            __DIR__.'/files/file.pdf',
            __DIR__.'/files/anotherFile.pdf',
        ];
    }

    /** @test */
    public function it_can_merge_multiple_files_into_one_file()
    {
        $pdf = EasyPdf::merge($this->files)
            ->content();

        $finfo = new finfo(1040);
        $buffer = $finfo->buffer($pdf);

        $this->assertSame('application/pdf; charset=binary', $buffer);
    }

    /** @test */
    public function it_can_add_watermark_into_the_file()
    {
        $easyPdf = new EasyPdf();
        $pdf = $easyPdf->reset()
            ->merge([$this->file])
            ->addWatermark(__DIR__.'/files/watermark.png')
            ->content();

        $finfo = new finfo(1040);
        $buffer = $finfo->buffer($pdf);

        $this->assertSame('application/pdf; charset=binary', $buffer);
    }
}
