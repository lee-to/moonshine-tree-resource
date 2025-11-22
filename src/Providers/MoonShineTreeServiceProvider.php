<?php

declare(strict_types=1);

namespace Leeto\MoonShineTree\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class MoonShineTreeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-tree');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/tree.php');

        Blade::withoutDoubleEncoding();
        Blade::componentNamespace('Leeto\MoonShineTree\View\Components', 'moonshine-tree');

        $this->commands([]);

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/moonshine-tree'),
        ], ['moonshine-tree-assets', 'laravel-assets']);
    }
}
