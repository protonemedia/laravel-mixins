<?php

namespace ProtoneMedia\Mixins\Tests\Request;

use Illuminate\Foundation\Http\FormRequest;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;
use ZipArchive;

class ImageRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64ImageKeys(): array
    {
        return [
            'png_image'  => 'Logo1.png',
            'jpeg_image' => 'Logo2.jpeg',
        ];
    }

    public function rules()
    {
        return [
            'png_image'  => ['required', 'file', 'image'],
            'jpeg_image' => ['required', 'file', 'image'],
        ];
    }
}

class ZipRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64ImageKeys(): array
    {
        return [
            'zip' => 'Logo.zip',
        ];
    }

    public function rules()
    {
        return [
            'zip' => ['required', 'file'],
        ];
    }
}

class ConvertsBase64ToFilesTest extends TestCase
{
    /** @test */
    public function it_converts_the_base64_images_to_illuminate_file_uploads()
    {
        $request = ImageRequest::create('/', 'POST', [
            'png_image'  => file_get_contents(__DIR__ . '/base64_png'),
            'jpeg_image' => file_get_contents(__DIR__ . '/base64_jpeg'),
        ]);

        $request->setContainer($this->app);
        $request->validateResolved();

        $pngFile  = $request->file('png_image');
        $jpegFile = $request->file('jpeg_image');

        $this->assertNotNull($pngFile);
        $this->assertNotNull($jpegFile);

        $this->assertEquals($pngFile, $request->validated()['png_image']);
        $this->assertEquals($jpegFile, $request->validated()['jpeg_image']);

        //

        $pngSize  = getimagesize($pngFile->getRealPath());
        $jpegSize = getimagesize($jpegFile->getRealPath());

        $this->assertEquals('Logo1.png', $pngFile->getClientOriginalName());
        $this->assertEquals('Logo2.jpeg', $jpegFile->getClientOriginalName());

        $this->assertEquals('width="300" height="300"', $pngSize[3]);
        $this->assertEquals('width="300" height="300"', $jpegSize[3]);
    }

    /** @test */
    public function it_converts_a_base64_zip_to_an_illuminate_file_upload()
    {
        $request = ZipRequest::create('/', 'POST', [
            'zip' => file_get_contents(__DIR__ . '/base64_zip'),
        ]);

        $request->setContainer($this->app);
        $request->validateResolved();

        $zipFile = $request->file('zip');

        $this->assertNotNull($zipFile);
        $this->assertEquals($zipFile, $request->validated()['zip']);

        $this->assertTrue(
            (new ZipArchive)->open($zipFile->getRealPath(), ZipArchive::CHECKCONS)
        );
    }
}
