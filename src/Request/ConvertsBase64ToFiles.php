<?php

namespace ProtoneMedia\LaravelMixins\Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;

trait ConvertsBase64ToFiles
{
    protected function base64FileKeys(): array
    {
        return [];
    }

    /**
     * Helper method to get the body parameters bag.
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function bodyParametersBag(): ParameterBag
    {
        return $this->request;
    }

    /**
     * Helper method to get the uploaded files bag.
     *
     * @return FileBag
     */
    private function uploadFilesBag(): FileBag
    {
        return $this->files;
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
        $flattened = Arr::dot($this->base64FileKeys());

        Collection::make($flattened)->each(function ($filename, $key) {
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

                $body = $this->bodyParametersBag()->all();
                Arr::forget($body, $key);
                $this->bodyParametersBag()->replace($body);

                $files = $this->uploadFilesBag()->all();
                Arr::set($files, $key, $uploadedFile);
                $this->uploadFilesBag()->replace($files);
            }, null, false);
        });
    }
}
