@if(!empty($items[0]))
    <div
        @if($resource->wrapable())
        x-data="{tree_show_all: $persist(true).as('tree_resource_all')}"
        @endif
    >
        @if($resource->wrapable())
        <a @click.stop="tree_show_all = !tree_show_all">
            <x-moonshine::icon icon="heroicons.chevron-up-down" />
        </a>
        @endif

        <ul @if($resource->sortable())
                x-data="sortable('{{ $route }}', 'nested')"
                data-id=""
                x-show="tree_show_all"
                data-handle=".handle"
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
