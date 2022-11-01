<?php

namespace TarfinLabs\EasyPdf\Tests;

use finfo;
use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class ParserTest extends TestCase
{
    /**
     * @var string
     */
    protected $file;

    protected function setUp(): void
    {
        parent::setUp();

        $this->file = __DIR__.'/files/multiple.pdf';
    }

    /** @test */
    public function it_can_return_pdf_page_count()
    {
        $fileCount = EasyPdf::parser($this->file)
            ->count();

        $this->assertEquals(3, $fileCount);
    }

    /** @test */
    public function it_can_use_pdf_content_as_a_parameter()
    {
        $content = file_get_contents($this->file);

        $fileCount = EasyPdf::parser($content)
            ->count();

        $this->assertEquals(3, $fileCount);
    }

    /** @test */
    public function it_can_parse_pdf_by_page()
    {
        $file = EasyPdf::parser($this->file)
            ->setPage(2)
            ->content();

        $finfo = new finfo(1040);
        $buffer = $finfo->buffer($file);

        $this->assertSame('application/pdf; charset=binary', $buffer);
    }

    /** @test */
    public function it_can_return_pdf_content()
    {
        $file = EasyPdf::parser($this->file)
            ->setPage(2)
            ->content();

        $finfo = new finfo(1040);
        $buffer = $finfo->buffer($file);

        $this->assertSame('application/pdf; charset=binary', $buffer);
    }

    /** @test */
    public function it_can_split_one_file_to_multiple_files()
    {
        $pdfs = EasyPdf::parser($this->file)
            ->splitTo(1);

        foreach ($pdfs as $pdf) {
            $finfo = new finfo(FILEINFO_MIME);
            $buffer = $finfo->buffer($pdf);
            $this->assertSame('application/pdf; charset=binary', $buffer);
        }

        $this->assertCount(3, $pdfs);
    }
}
