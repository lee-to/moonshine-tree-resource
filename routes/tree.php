<?php

declare(strict_types=1);

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Leeto\MoonShineTree\Resources\TreeResource;
use MoonShine\Laravel\Http\Requests\Resources\ViewAnyFormRequest;

Route::moonshine(static function () {
    Route::post('sortable', static function (ViewAnyFormRequest $request) {
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

        if ($request->filled('data')) {
            $ids = $request->str('data')
                ->explode(',')
                ->values();

            foreach ($ids as $index => $id) {
                $query = $model->newModelQuery()->where($keyName, $id);

                if ($resource->treeKey()) {
                    $query->where($resource->treeKey(), $request->get('parent'));
                }

                $query->update([
                    $resource->sortKey() => (int) $index,
                ]);
            }
        }

        return response()->noContent();
    })->name('sortable');
}, withResource: true, withPage: false, withAuthenticate: true);
