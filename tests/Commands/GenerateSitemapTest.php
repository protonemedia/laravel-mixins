<?php

namespace Protonemedia\Mixins\Tests\Commands;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemapTest extends TestCase
{
    #[Test]
    /** @test */
    public function it_stores_the_sitemap_at_the_public_path()
    {
        $generator = Mockery::spy(SitemapGenerator::class);

        $generator->allows('setUrl')->andReturnSelf();
        $generator->allows('writeToFile')->andReturnSelf();

        (new GenerateSitemap)->handle($generator);

        $generator->shouldHaveReceived('setUrl')->with('http://localhost')->once();
        $generator->shouldHaveReceived('writeToFile')->with(public_path('sitemap.xml'))->once();
    }

    #[Test]
    /** @test */
    public function it_can_be_added_to_the_console()
    {
        GenerateSitemap::register();
        Artisan::call('list');

        $this->assertStringContainsString('sitemap:generate', Artisan::output());

        //

        GenerateSitemap::register('generate-sitemap');
        Artisan::call('list');

        $this->assertStringContainsString('generate-sitemap', Artisan::output());
    }
}
