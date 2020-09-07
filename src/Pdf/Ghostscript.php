<?php

namespace ProtoneMedia\LaravelMixins\Pdf;

use Symfony\Component\Process\Process;

class Ghostscript implements CanRegeneratePDF
{
    private $bin;

    public function __construct(string $bin = 'ghostscript')
    {
        $this->bin = $bin;
    }

    private static function tempFile(): string
    {
        return tempnam(sys_get_temp_dir(), 'ghostscript') . '.pdf';
    }

    public function regeneratePdf(string $pdfContent): string
    {
        file_put_contents($input = static::tempFile(), $pdfContent);

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
