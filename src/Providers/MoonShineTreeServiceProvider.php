<?php

declare(strict_types=1);

namespace VendorName\PackageName\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class PackageNameServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-tree');

        Blade::withoutDoubleEncoding();
        Blade::componentNamespace('VendorName\PackageName\View\Components', 'moonshine-tree');

        $this->commands([]);
    }
}
