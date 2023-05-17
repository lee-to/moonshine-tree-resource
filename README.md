## MoonShine sortable tree resource

<p align="center">
<a href="https://moonshine.cutcode.dev" target="_blank">
<img src="https://github.com/lee-to/moonshine-tree-resource/blob/master/art/screenshot.png">
</a>
</p>

### Requirements

- MoonShine v1.57+

### Installation

```shell
composer require lee-to/moonshine-tree-resource
```

### Get started

Example usage with tree

```php
use Leeto\MoonShineTree\Resources\TreeResource;

class CategoryResource extends TreeResource
{
    public static string $orderField = 'sorting';

    // ... fields, model, etc ...

    public function treeKey(): ?string
    {
        return 'parent_id';
    }

    public function sortKey(): string
    {
        return 'sorting';
    }

    // ...
}
```

Just a sortable usage

```php
use Leeto\MoonShineTree\Resources\TreeResource;

class CategoryResource extends TreeResource
{
    public static string $orderField = 'sorting';

    // ... fields, model, etc ...

    public function treeKey(): ?string
    {
        return null;
    }

    public function sortKey(): string
    {
        return 'sorting';
    }

    // ...
}
```
