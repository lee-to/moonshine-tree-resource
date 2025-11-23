@props([
    'resource',
    'item',
    'items',
    'buttons',
])

@php
    /** @var \Leeto\MoonShineTree\Resources\TreeResource $resource */

    $hash = 'tree_' . md5($item->getKey());
@endphp

<li class="tree__item"
    data-id="{{ $item->getKey() }}"
    @if($resource->wrappable())
        x-data="{show_{{ $hash }}: $persist(true).as('tree_resource_{{ $hash }}')}"
    @endif
>
    <div class="tree__node">
        <div class="tree__content">
            <div class="tree__main">
                @if($resource->sortable())
                    <div class="tree__handle">
                        <x-moonshine::icon icon="bars-3-bottom-right"/>
                    </div>
                @endif

                <div class="tree__title-section">
                    @if($resource->treeItemBadgeText($item))
                        <x-moonshine::badge
                            color="{{ $resource->treeItemBadgeColor($item) }}"
                            class="tree__badge">
                            {{ $resource->treeItemBadgeText($item) }}
                        </x-moonshine::badge>
                    @endif
                    @if($resource->treeItemTitle($item))
                        <div class="tree__title-text">
                            {{ $resource->treeItemTitle($item) }}
                        </div>
                    @endif
                </div>

                @if($resource->treeItemDescription($item))
                    <div class="tree__description">
                        {!! $resource->treeItemDescription($item) !!}
                    </div>
                @endif
            </div>

            @if($resource->wrappable() && isset($items[$item->getKey()]))
                <button
                    @click.stop="show_{{ $hash }} = !show_{{ $hash }}"
                    class="tree__toggle"
                    :class="show_{{ $hash }} ? '' : 'icon--collapsed'">
                    <x-moonshine::icon icon="chevron-up"/>
                </button>
            @endif

            <div class="tree__actions">
                <x-moonshine::action-group :actions="$buttons($item)"/>
            </div>
        </div>

        @if($resource->treeKey())
            <ul
                class="tree__children tree__drop-zone"
                x-show="show_{{ $hash }}"
                @if($resource->sortable())
                    x-data="sortable('{{ $resource->getRoute('sortable') }}', 'nested')"
                data-id="{{ $item->getKey() }}"
                data-handle=".tree__handle"
                data-animation="200"
                data-fallbackOnBody="true"
                data-swapThreshold="0.8"
                @endif
            >
                @if(isset($items[$item->getKey()]))
                    @foreach($items[$item->getKey()] as $inner)
                        <x-moonshine-tree::tree.item
                            :items="$items"
                            :item="$inner"
                            :resource="$resource"
                            :buttons="$buttons"
                        />
                    @endforeach
                @endif
            </ul>
        @endif
    </div>
</li>
