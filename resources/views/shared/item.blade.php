<li class="my-4" data-id="{{ $item->getKey() }}">
    <x-moonshine::box>
        <div class="flex justify-between items-center gap-4">
            <div class="handle cursor-pointer flex justify-start items-center gap-4">
                <x-moonshine::icon icon="heroicons.bars-3-bottom-right" />

                <div class="font-bold">
                    <x-moonshine::badge color="purple">{{ $item->getKey() }}</x-moonshine::badge>
                    {{ $item->{$resource->titleField()} }}
                </div>
            </div>

            <div class="flex justify-between items-center gap-4">
                @include('moonshine::crud.shared.item-actions', [
                    'resource' => $resource,
                    'except' => []
                ])
            </div>
        </div>

        @if($resource->treeKey())
            <ul x-data="sortable"
                class="dropzone my-4"
                data-tree_key="{{ $item->getKey() }}">

                @if(isset($data[$item->getKey()]))
                    @foreach($data[$item->getKey()] as $inner)
                        @include('moonshine-tree::shared.item', [
                            'data' => $data,
                            'item' => $inner,
                            'resource' => $resource->setItem($inner)
                        ])
                    @endforeach
                @endif
            </ul>
        @endif
    </x-moonshine::box>
</li>
