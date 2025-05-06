## MoonShine sortable tree resource

<p align="center">
<a href="https://moonshine-laravel.com" target="_blank">
<img src="https://github.com/lee-to/moonshine-tree-resource/blob/2.x/art/screenshot.png">
</a>
</p>

### Requirements

- MoonShine v3.0+

### Support MoonShine versions

| MoonShine   | TreeResource |
|-------------|------|
| 2.0+        | 1.0+ |
| 3.0+        | 2.0+ |

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
    // Required
    protected string $column = 'title';

    protected string $sortColumn = 'sorting';

    protected function pages(): array
    {
        return [
            CategoryTreePage::class,
            FormPage::class,
            DetailPage::class,
        ];
    }

    // ... fields, model, etc ...

    public function treeKey(): ?string
    {
        return 'parent_id';
    }

    public function sortKey(): string
    {
        return 'sorting';
    }

    public function showBadge(): bool
    {
        return true;
    }

    // ...
}
```

And add component

```php
namespace App\MoonShine\Pages;

use Leeto\MoonShineTree\View\Components\TreeComponent;
use MoonShine\Laravel\Pages\Crud\IndexPage;

class CategoryTreePage extends IndexPage
{
    protected function mainLayer(): array
    {
        return [
            ...$this->getPageButtons(),
            TreeComponent::make($this->getResource()),
        ];
    }
}
```

Or modify index component from resource

```php
protected string $sortColumn = 'sorting';

public function modifyListComponent(ComponentContract $component): ComponentContract
{
    return TreeComponent::make($this);
}
```
Just a sortable usage

```php
use Leeto\MoonShineTree\Resources\TreeResource;

class CategoryResource extends TreeResource
{
    // Required
    protected string $column = 'title';

    protected string $sortColumn = 'sorting';

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

### Additional content

```php
public function itemContent(Model $item): string
{
    return 'Custom content here';
}
```

### Turn off sortable or wrapable

```php
public function wrapable(): bool
{
    return false;
}

public function sortable(): bool
{
    return false;
}
```
### Turn off badge
```php
public function showBadge(): bool
{
    return true;
}
```
