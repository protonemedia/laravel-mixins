<?php

namespace Tests\Unit\Request;

use Illuminate\Foundation\Http\FormRequest;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ImagesToFiles;
use ZipArchive;

class ImageRequest extends FormRequest
{
    use ConvertsBase64ImagesToFiles;

    protected function base64ImageKeys(): array
    {
        return [
            'png_image'  => 'Logo1.png',
            'jpeg_image' => 'Logo2.jpeg',
        ];
    }

    public function rules()
    {
        return [];
    }
}

class ZipRequest extends FormRequest
{
    use ConvertsBase64ImagesToFiles;

    protected function base64ImageKeys(): array
    {
        return [
            'zip' => 'Logo.zip',
        ];
    }

    public function rules()
    {
        return [];
    }
}

class ConvertsBase64ImagesToFilesTest extends TestCase
{
    /** @test */
    public function it_converts_the_base64_images_to_illuminate_file_uploads()
    {
        $request = ImageRequest::create('/', 'POST', [
            'png_image'  => file_get_contents(__DIR__ . '/base64_png'),
            'jpeg_image' => file_get_contents(__DIR__ . '/base64_jpeg'),
        ]);

        $request->setContainer(app());
        $request->validateResolved();

        $pngFile  = $request->file('png_image');
        $jpegFile = $request->file('jpeg_image');

        $this->assertNotNull($pngFile);
        $this->assertNotNull($jpegFile);

        $pngSize  = getimagesize($pngFile->getRealPath());
        $jpegSize = getimagesize($jpegFile->getRealPath());

        $this->assertEquals('Logo1.png', $pngFile->getClientOriginalName());
        $this->assertEquals('Logo2.jpeg', $jpegFile->getClientOriginalName());

        $this->assertEquals('width="300" height="300"', $pngSize[3]);
        $this->assertEquals('width="300" height="300"', $jpegSize[3]);
    }

    /** @test */
    public function it_converts_a_base64_zip_to_a_illuminate_file_upload()
    {
        $request = ZipRequest::create('/', 'POST', [
            'zip' => file_get_contents(__DIR__ . '/base64_zip'),
        ]);

        $request->setContainer(app());
        $request->validateResolved();

        $zipFile = $request->file('zip');

        $this->assertNotNull($zipFile);
        $this->assertTrue(
            (new ZipArchive)->open($zipFile->getRealPath(), ZipArchive::CHECKCONS)
        );
    }
}
