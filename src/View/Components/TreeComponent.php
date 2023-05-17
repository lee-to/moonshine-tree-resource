<?php

namespace App\View\Components;

use App\MoonShine\Resources\TreeResource;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TreeComponent extends Component
{
    public function __construct(
        public TreeResource $resource,
        public Collection $items
    ) {
    }

    public function render(): View
    {
        return view('admin.categories.tree-component')
            ->with('data', $this->resource->performTree($this->items));
    }
}
