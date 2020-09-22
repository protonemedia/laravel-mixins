<?php

namespace ProtoneMedia\LaravelMixins\Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait ConvertsBase64ToFiles
{
    /**
     * Specify all input keys that should be converted.
     *
     * @return array
     */
    protected function base64ImageKeys(): array
    {
        return [];
    }

    /**
     * Pulls the Base64 contents for each image key and creates
     * an UploadedFile instance from it and sets it on the
     * request.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        Collection::make($this->base64ImageKeys())->each(function ($filename, $key) {
            rescue(function () use ($key, $filename) {
                $base64Contents = $this->input($key);

                if (!$base64Contents) {
                    return;
                }

                // Generate a temporary path to store the Base64 contents
                $tempFilePath = tempnam(sys_get_temp_dir(), $filename);

                // Store the contents using a stream, or by decoding manually
                if (Str::startsWith($base64Contents, 'data:') && count(explode(',', $base64Contents)) > 1) {
                    $source = fopen($base64Contents, 'r');
                    $destination = fopen($tempFilePath, 'w');

                    stream_copy_to_stream($source, $destination);

                    fclose($source);
                    fclose($destination);
                } else {
                    file_put_contents($tempFilePath, base64_decode($base64Contents, true));
                }

                $uploadedFile = new UploadedFile($tempFilePath, $filename, null, null, true);

                $this->request->remove($key);
                $this->files->set($key, $uploadedFile);
            }, null, false);
        });
    }
}
