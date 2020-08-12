<?php

namespace ProtoneMedia\LaravelMixins\Pdf;

interface CanRegeneratePDF
{
    public function regeneratePdf(string $pdfContent): string;
}
