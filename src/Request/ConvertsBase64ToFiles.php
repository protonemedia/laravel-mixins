<?php

namespace ProtoneMedia\LaravelMixins\Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait ConvertsBase64ToFiles
{
    protected function base64ImageKeys(): array
    {
        return [];
    }

    protected function prepareForValidation()
    {
        Collection::make($this->base64ImageKeys())->each(function ($filename, $key) {
            rescue(function () use ($key, $filename) {
                $base64Value = $this->input($key);

                if (!$base64Value) {
                    return;
                }

                $tmpFilePath = tempnam(sys_get_temp_dir(), $filename);

                if (Str::startsWith($base64Value, 'data:') && count(explode(',', $base64Value)) > 1) {
                    $source = fopen($base64Value, 'r');
                    $destination = fopen($tmpFilePath, 'w');

                    stream_copy_to_stream($source, $destination);

                    fclose($source);
                    fclose($destination);
                } else {
                    file_put_contents($tmpFilePath, base64_decode($base64Value, true));
                }

                $uploadedFile = new UploadedFile($tmpFilePath, $filename, null, null, true);

                $this->request->remove($key);
                $this->files->set($key, $uploadedFile);
            }, null, false);
        });
    }
}
