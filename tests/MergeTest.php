<?php

namespace TarfinLabs\EasyPdf\Tests;

use finfo;
use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class MergeTest extends TestCase
{
    protected $files;

    protected function setUp(): void
    {
        parent::setUp();

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
}
