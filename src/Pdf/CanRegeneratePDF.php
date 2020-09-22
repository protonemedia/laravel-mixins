<?php

namespace ProtoneMedia\LaravelMixins\Pdf;

interface CanRegeneratePDF
{
    /**
     * Opens the PDF contents, regenerates it, and then returns the new contents.
     *
     * @param string $pdfContents
     * @return string
     */
    public function regeneratePdf(string $pdfContents): string;
}
