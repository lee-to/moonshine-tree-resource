<?php

declare(strict_types=1);

namespace Leeto\MoonShineTree\Resources;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\Action;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Support\ListOf;

abstract class TreeResource extends ModelResource
{
    protected SortDirection $sortDirection = SortDirection::ASC;

    protected bool $usePagination = false;

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::MASS_DELETE);
    }

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

    public function showBadge(): bool
    {
        return true;
    }

}
