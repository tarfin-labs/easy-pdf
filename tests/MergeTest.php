<?php

namespace TarfinLabs\EasyPdf\Tests;

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
            __DIR__.'/files/anotherFile.pdf'
        ];
    }

    /** @test */
    public function it_can_merge_multiple_files_into_one_file()
    {
        $filePath = sys_get_temp_dir().'merged.pdf';

        EasyPdf::merge($this->files)
            ->save($filePath);

        $this->assertFileExists($filePath);

        $count = EasyPdf::parser($filePath)
            ->count();

        $this->assertEquals(2, $count);

    }
}
