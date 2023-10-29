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

        Blade::withoutDoubleEncoding();
        Blade::componentNamespace('Leeto\MoonShineTree\View\Components', 'moonshine-tree');

        $this->commands([]);
    }
}
