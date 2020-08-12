<?php

namespace Tests\Unit\Accounting;

use Illuminate\Support\Str;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Pdf\Ghostscript;

class GhostscriptTest extends TestCase
{
    /** @test */
    public function it_returns_a_string_of_regenerates_pdf_content()
    {
        $regeneratedPdf = (new Ghostscript)->regeneratePdf(
            file_get_contents(__DIR__ . '/dummy.pdf')
        );

        $this->assertTrue(Str::contains($regeneratedPdf, 'PDF-1.'));
        $this->assertTrue(Str::contains($regeneratedPdf, 'youtube.com'));
        $this->assertTrue(Str::contains($regeneratedPdf, 'Producer(GPL Ghostscript'));
    }
}
