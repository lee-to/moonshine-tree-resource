<?php

declare(strict_types=1);

namespace Leeto\MoonShineTree\View\Components;

use MoonShine\Components\MoonShineComponent;
use MoonShine\Resources\ModelResource;
use MoonShine\Traits\HasResource;

/**
 * @method static static make(ModelResource $resource)
 */
final class TreeComponent extends MoonShineComponent
{
    use HasResource;

    protected string $view = 'moonshine-tree::components.tree.index';

    public function __construct(ModelResource $resource)
    {
        $this->setResource($resource);
    }

    protected function items(): array
    {
        $performed = [];
        $resource = $this->getResource();
        $items = $resource->items();

        foreach ($items as $item) {
            $parent = is_null($resource->treeKey()) || is_null($item->{$resource->treeKey()})
                ? 0
                : $item->{$resource->treeKey()};

            $performed[$parent][$item->getKey()] = $item;
        }

        return $performed;
    }

    protected function viewData(): array
    {
        return [
            'items' => $this->items(),
            'resource' => $this->getResource(),
            'route' => $this->getResource()->route('sortable'),
        ];
    }
}
