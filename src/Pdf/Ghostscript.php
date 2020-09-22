<?php

namespace ProtoneMedia\LaravelMixins\Pdf;

use Symfony\Component\Process\Process;

class Ghostscript implements CanRegeneratePDF
{
    /**
     * Ghostscript binary path.
     */
    private string $bin;

    public function __construct(string $bin = 'ghostscript')
    {
        $this->bin = $bin;
    }

    /**
     * Generates a temporary filename for a PDF file.
     *
     * @return string
     */
    private static function tempFile(): string
    {
        return tempnam(sys_get_temp_dir(), 'ghostscript') . '.pdf';
    }

    /**
     * {@inheritdoc}
     */
    public function regeneratePdf(string $pdfContents): string
    {
        file_put_contents($input = static::tempFile(), $pdfContents);

        (new Process([
            $this->bin,
            '-sDEVICE=pdfwrite',
            '-dPDFSETTINGS=/prepress',
            '-sOutputFile=' . $destination = static::tempFile(),
            $input,
        ]))->run();

        return tap(@file_get_contents($destination), function () use ($input, $destination) {
            @unlink($input);
            @unlink($destination);
        });
    }
}
