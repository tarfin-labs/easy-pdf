<?php

namespace TarfinLabs\EasyPdf\Tests;

use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class ParserTest extends TestCase
{
    /**
     * @var string
     */
    protected string $file;

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
        $filePath = sys_get_temp_dir().'new.png';

        $file = EasyPdf::parser($this->file)
            ->setPage(2)
            ->save($filePath);

        $fileCount = EasyPdf::parser($filePath)
            ->count();

        $this->assertEquals(1, $fileCount);
    }
}
