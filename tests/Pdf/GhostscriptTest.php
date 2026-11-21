<?php

namespace ProtoneMedia\Mixins\Tests\Pdf;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Str;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Pdf\Ghostscript;

class GhostscriptTest extends TestCase
{
    #[Test]
    /** @test */
    public function it_returns_a_string_of_regenerates_pdf_content()
    {
        $binary = (PHP_OS === 'Darwin') ? 'gs' : 'ghostscript';

        $originalPdf = file_get_contents(__DIR__ . '/dummy.pdf');

        $regeneratedPdf = (new Ghostscript($binary))->regeneratePdf($originalPdf);

        $this->assertNotEmpty($regeneratedPdf);
        $this->assertNotSame($originalPdf, $regeneratedPdf);

        $this->assertTrue(Str::contains($regeneratedPdf, 'PDF-1.'));
    }
}
