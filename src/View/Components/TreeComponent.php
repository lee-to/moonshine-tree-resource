<?php

declare(strict_types=1);

namespace Leeto\MoonShineTree\View\Components;

use Leeto\MoonShineTree\Resources\TreeResource;
use MoonShine\Core\Traits\HasResource;
use MoonShine\Laravel\Resources\CrudResource;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\MoonShineComponent;

/**
 * @method static static make(ModelResource $resource)
 */
final class TreeComponent extends MoonshineComponent
{
    /** @use HasResource<CrudResource, CrudResource> */
    use HasResource;

    protected string $view = 'moonshine-tree::components.tree.index';

    public function __construct(ModelResource $resource)
    {
        parent::__construct();

        $this->setResource($resource);
    }

    protected function items(): array
    {
        $performed = [];
        /** @var TreeResource $resource */
        $resource = $this->getResource();
        $items = $resource?->getItems() ?? [];

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
            'route' => $this->getResource()?->getRoute('sortable'),
            'buttons' => function ($item) {
                $resource = $this
                    ->getResource()
                    ?->setItem($item);

                return $resource
                    ?->getIndexButtons()
                    ->fill($resource->getCastedData())
                    ?->withoutBulk();
            },
        ];
    }
}
