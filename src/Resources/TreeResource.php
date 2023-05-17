<?php

namespace Leeto\MoonShineTree\Resources;

use DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use MoonShine\Http\Requests\Resources\ViewAnyFormRequest;
use MoonShine\Resources\Resource;

abstract class TreeResource extends Resource
{
    protected bool $usePagination = false;
    protected string $itemsView = 'moonshine-tree::items';

    public static string $orderType = 'ASC';

    abstract public function treeKey(): ?string;

    abstract public function sortKey(): string;

    public function search(): array
    {
        return [];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function performTree(Collection $resources): array
    {
        $performed = [];

        foreach ($resources as $resource) {
            $item = $resource->getItem();
            $parent = is_null($this->treeKey()) || is_null($item->{$this->treeKey()})
                ? 0
                : $item->{$this->treeKey()};

            $performed[$parent][$item->getKey()] = $item;
        }

        return $performed;
    }

    public function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::prefix('resource')->group(function () {
            Route::post("{$this->uriKey()}/sortable", function (ViewAnyFormRequest $request) {
                $keyName = $request->getResource()->getModel()->getKeyName();
                $model = $request->getResource()->getModel();

                if ($keyName === $this->sortKey()) {
                    throw new InvalidArgumentException('Primary key cannot be used as a sort');
                }

                if ($this->treeKey()) {
                    $model->newQuery()
                        ->where($keyName, $request->get('id'))
                        ->update([
                            $this->sortKey() => $request->integer('index'),
                            $this->treeKey() => $request->get('parent')
                        ]);
                }


                $caseStatement = $request->str('data')
                    ->explode(',')
                    ->implode(fn($id, $index) => "WHEN {$id} THEN {$index} ");

                $model->newQuery()
                    ->when(
                        $this->treeKey(),
                        fn(Builder $q) => $q->where($this->treeKey(), $request->get('parent'))
                    )
                    ->update([
                        $this->sortKey() => DB::raw("CASE $keyName $caseStatement END")
                    ]);

                return response()->noContent();
            })->name($this->routeNameAlias().'.sortable');
        });
    }
}
