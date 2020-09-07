<?php

namespace Protonemedia\Mixins\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Mockery;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemapTest extends TestCase
{
    /** @test */
    public function it_stores_the_sitemap_at_the_public_path()
    {
        $command = new GenerateSitemap(
            $generator = Mockery::mock(SitemapGenerator::class)
        );

        $generator->shouldReceive('setUrl')
            ->with('http://localhost')
            ->andReturnSelf();

        $generator->shouldReceive('writeToFile')
            ->with(public_path('sitemap.xml'))
            ->andReturnSelf();

        $command->handle();
    }

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
