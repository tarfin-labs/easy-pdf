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
}
