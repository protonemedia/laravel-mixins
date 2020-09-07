<?php

namespace ProtoneMedia\LaravelMixins\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Sitemap Generator
     */
    private SitemapGenerator $sitemapGenerator;

    /**
     * Set the Sitemap Generator
     *
     * @param \Spatie\Sitemap\SitemapGenerator $sitemapGenerator
     */
    public function __construct(SitemapGenerator $sitemapGenerator)
    {
        $this->sitemapGenerator = $sitemapGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sitemapGenerator
            ->setUrl(config('app.url'))
            ->writeToFile(public_path('sitemap.xml'));
    }
}
