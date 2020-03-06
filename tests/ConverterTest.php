<?php

namespace TarfinLabs\EasyPdf\Tests;

use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class ConverterTest extends TestCase
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
    public function it_can_convert_pdf_to_image_as_png()
    {
        $filePath = sys_get_temp_dir().'new.png';

        EasyPdf::convert($this->file)
            ->toImage($filePath);

        $this->assertFileExists($filePath);
        $this->assertSame('image/png', mime_content_type($filePath));
    }
}
