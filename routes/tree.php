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
}, withResource: true, withPage: false, withAuthenticate: true);
