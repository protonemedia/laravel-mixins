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
        $binary = (PHP_OS === 'Darwin') ? 'gs' : 'ghostscript';

        $regeneratedPdf = (new Ghostscript($binary))->regeneratePdf(
            file_get_contents(__DIR__ . '/dummy.pdf')
        );

        $this->assertNotEmpty($regeneratedPdf);

        $this->assertTrue(Str::contains($regeneratedPdf, 'PDF-1.'));
        $this->assertTrue(Str::contains($regeneratedPdf, 'Producer(GPL Ghostscript'));
    }
}
