<?php

namespace Protonemedia\Mixins\Tests\Commands;

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
}
