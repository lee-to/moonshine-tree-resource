@if(isset($data[0]))
<ul x-data="sortable" data-tree_key="">
    @foreach($data[0] as $item)
        @include('admin.categories.shared.item', [
            'data' => $data,
            'item' => $item,
            'resource' => $resource->setItem($item)
        ])
    @endforeach
</ul>
@endif

<script>
    function sortable() {
        return {
            init() {
                Sortable.create(this.$el, {
                    group: {
                        name: 'nested'
                    },
                    handle: '.handle',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    dataIdAttr: 'data-id',

                    onSort: async function (evt) {
                        let formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('id', evt.item.dataset.id);
                        formData.append('parent', evt.to.dataset.tree_key);
                        formData.append('index', evt.newIndex);
                        formData.append('data', this.toArray());

                        await fetch('{{ $resource->route('sortable') }}', {
                            body: formData,
                            method: "post",
                        })
                    }
                });
            }
        }
    }
</script>
