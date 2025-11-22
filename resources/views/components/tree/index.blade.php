@php
/** @var \Leeto\MoonShineTree\Resources\TreeResource $resource */
@endphp

@if(!empty($items[0]))
    <div x-data="{tree_show_all: $persist(true).as('tree_resource_all')}" class="tree-wrapper">
        @if($resource->wrapable())
        <button @click.stop="tree_show_all = !tree_show_all" class="tree-expand-all">
            <x-moonshine::icon icon="chevron-up-down" />
        </button>
        @endif

        <ul
            class="tree"
            x-show="tree_show_all"
            @if($resource->sortable())
                x-data="sortable('{{ $route }}', 'nested')"
                data-id=""
                data-handle=".tree__handle"
                data-animation="150"
                data-fallbackOnBody="true"
                data-swapThreshold="0.65"
            @endif
        >
            @foreach($items[0] as $item)
                <x-moonshine-tree::tree.item
                    :items="$items"
                    :item="$item"
                    :resource="$resource"
                    :buttons="$buttons"
                />
            @endforeach
        </ul>
    </div>
@endif
