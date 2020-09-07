<?php

namespace ProtoneMedia\LaravelMixins\Blade;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\View\ViewServiceProvider;

trait TestsBladeComponents
{
    protected function setViewPath($path)
    {
        Config::set('view.paths', Arr::wrap($path));

        tap(new ViewServiceProvider($this->app), function (ViewServiceProvider $viewServiceProvider) {
            $viewServiceProvider->registerViewFinder();
            $viewServiceProvider->register();
        });
    }

    protected function renderView(string $view, array $data = [])
    {
        Artisan::call('view:clear');

        $view = View::make($view, $data);

        return trim((string) ($view));
    }
}
