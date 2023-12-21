<?php

declare(strict_types=1);

namespace Leeto\MoonShineTree\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use MoonShine\Http\Requests\Resources\ViewAnyFormRequest;
use MoonShine\Resources\ModelResource;

abstract class TreeResource extends ModelResource
{
    protected string $sortDirection = 'ASC';

    protected bool $usePagination = false;

    abstract public function treeKey(): ?string;

    abstract public function sortKey(): string;

    public function itemContent(Model $item): string
    {
        return '';
    }

    public function sortable(): bool
    {
        return true;
    }

    public function wrapable(): bool
    {
        return true;
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('sortable', function (ViewAnyFormRequest $request) {
            /** @var TreeResource $resource */
            $resource = $request->getResource();
            $keyName = $resource->getModel()->getKeyName();
            $model = $resource->getModel();

            if ($keyName === $resource->sortKey()) {
                throw new InvalidArgumentException('Primary key cannot be used as a sort');
            }

            if ($resource->treeKey()) {
                $model->newModelQuery()
                    ->firstWhere($keyName, $request->get('id'))
                    ?->update([
                        $resource->sortKey() => $request->integer('index'),
                        $resource->treeKey() => $request->get('parent')
                    ]);
            }


            if ($request->str('data')->isNotEmpty()) {
                $caseStatement = $request->str('data')
                    ->explode(',')
                    ->implode(fn($id, $index) => "WHEN $id THEN $index ");

                $model->newModelQuery()
                    ->when(
                        $resource->treeKey(),
                        fn(Builder $q) => $q->where($resource->treeKey(), $request->get('parent'))
                    )
                    ->get()
                    ->each(function ($row) use($resource, $keyName, $caseStatement) {
                        $row->update([
                            $resource->sortKey() => DB::raw(
                                "CASE $keyName $caseStatement ELSE {$resource->sortKey()} END"
                            )
                        ]);
                    });

            }

            return response()->noContent();
        })->name('sortable');
    }
}
