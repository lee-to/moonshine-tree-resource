@props([
    'resource',
    'item',
    'items',
    'buttons',
])
<li class="my-4"
    data-id="{{ $item->getKey() }}"
    @if($resource->wrapable())
    x-data="{tree_show_{{ $item->getKey() }}: $persist(true).as('tree_resource_{{ $item->getKey() }}')}"
    @endif
>
    <x-moonshine::box>
        <div class="flex justify-between items-center gap-4">
            <div class="@if($resource->sortable()) handle cursor-pointer @endif flex justify-start items-center gap-4">
                @if($resource->sortable())
                    <x-moonshine::icon icon="heroicons.bars-3-bottom-right" />
                @endif

                <div class="font-bold">
                    <x-moonshine::badge color="purple">{{ $item->getKey() }}</x-moonshine::badge>
                    {{ $item->{$resource->column()} }}
                </div>

                @if($resource->wrapable())
                    <a @click.stop="tree_show_{{ $item->getKey() }} = !tree_show_{{ $item->getKey() }}">
                        <x-moonshine::icon icon="heroicons.chevron-up-down" />
                    </a>
                @endif

                {!! $resource->itemContent($item) !!}
            </div>

            <div class="flex justify-between items-center gap-4">
                <x-moonshine::action-group
                    :actions="$buttons($item)"
                />
            </div>
        </div>

        @if($resource->treeKey())
            <ul
                @if($resource->sortable())
                    x-data="sortable('{{ $resource->route('sortable') }}', 'nested')"
                    class="dropzone my-4"
                    x-show="tree_show_{{ $item->getKey() }}"
                    data-id="{{ $item->getKey() }}"
                    data-handle=".handle"
                    data-animation="150"
                    data-fallbackOnBody="true"
                    data-swapThreshold="0.65"
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
    </x-moonshine::box>
</li>
