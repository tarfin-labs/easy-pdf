<?php

namespace TarfinLabs\EasyPdf\Tests;

use finfo;
use PHPUnit\Framework\TestCase;
use TarfinLabs\EasyPdf\EasyPdf;

class PdfTest extends TestCase
{
    /** @test */
    public function it_can_convert_html_to_pdf()
    {
        $filePath = sys_get_temp_dir().'html.pdf';

        $html = '<p>This is just an example of html code to demonstrate some supported CSS inline styles.
            <span style="font-weight: bold;">bold text</span>
        </p>';

        $easyPdf = new EasyPdf();
        $pdf = $easyPdf->withInformation([
            'Title' => 'Tarfin',
            'Subject' => 'Tarfin',
            'Keywords' => 'easy-pdf, pdf',
            'Creator' => 'Tarfin',
            'Author' => 'Faruk Can',
            'AutoPageBreak' => [true, 0],
        ])
            ->withConfig([
                'ImageScale' => PDF_IMAGE_SCALE_RATIO,
            ])
            ->setFont('helvetica', 16)
            ->setDuplex('Simplex')
            ->loadHtml($html)
            ->content();

        $finfo = new finfo(1040);
        $buffer = $finfo->buffer($pdf);

        $this->assertSame('application/pdf; charset=binary', $buffer);
    }
}
