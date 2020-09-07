<?php

namespace Protonemedia\Mixins\Tests\Commands;

use Orchestra\Testbench\TestCase;
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemapTests extends TestCase
{
    /** @test */
    public function it_stores_the_sitemap_at_the_public_path()
    {
        $command = new GenerateSitemap(
            $generator = $this->mock(SitemapGenerator::class)
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
