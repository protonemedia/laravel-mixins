<?php

namespace ProtoneMedia\LaravelMixins\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
     * Set the signature.
     *
     * @param string $signature
     */
    public function __construct(string $signature = 'sitemap:generate')
    {
        $this->signature = $signature;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SitemapGenerator $sitemapGenerator)
    {
        $sitemapGenerator
            ->setUrl(config('app.url'))
            ->writeToFile(public_path('sitemap.xml'));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    public static function register(string $signature = 'sitemap:generate')
    {
        Artisan::registerCommand(new static($signature));
    }
}
