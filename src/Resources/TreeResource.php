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

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('sortable', function (ViewAnyFormRequest $request) {
            $keyName = $request->getResource()->getModel()->getKeyName();
            $model = $request->getResource()->getModel();

            if ($keyName === $this->sortKey()) {
                throw new InvalidArgumentException('Primary key cannot be used as a sort');
            }

            if ($this->treeKey()) {
                $model->newModelQuery()
                    ->firstWhere($keyName, $request->get('id'))
                    ?->update([
                        $this->sortKey() => $request->integer('index'),
                        $this->treeKey() => $request->get('parent')
                    ]);
            }


            if ($request->str('data')->isNotEmpty()) {
                $caseStatement = $request->str('data')
                    ->explode(',')
                    ->implode(fn($id, $index) => "WHEN {$id} THEN {$index} ");

                $model->newModelQuery()
                    ->when(
                        $this->treeKey(),
                        fn(Builder $q) => $q->where($this->treeKey(), $request->get('parent'))
                    )
                    ->get()
                    ->each(function ($row) use($keyName, $caseStatement) {
                        $row->update([
                            $this->sortKey() => DB::raw("CASE $keyName $caseStatement ELSE `{$this->sortKey()}` END")
                        ]);
                    });

            }

            return response()->noContent();
        })->name('sortable');
    }
}
