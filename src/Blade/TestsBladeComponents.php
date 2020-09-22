<?php

namespace ProtoneMedia\LaravelMixins\Blade;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\View\ViewServiceProvider;

trait TestsBladeComponents
{
    /**
     * Sets the view paths.
     *
     * @param array|string $path
     * @return void
     */
    protected function setViewPath($path)
    {
        Config::set('view.paths', Arr::wrap($path));

        tap(new ViewServiceProvider($this->app), function (ViewServiceProvider $viewServiceProvider) {
            $viewServiceProvider->registerViewFinder();
            $viewServiceProvider->register();
        });
    }

    /**
     * Returns the evaluated view contents for the given view and data.
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    protected function renderView(string $view, array $data = []): string
    {
        Artisan::call('view:clear');

        $view = View::make($view, $data);

        return trim((string) ($view));
    }
}
