## MoonShine sortable tree resource

<p align="center">
<a href="https://moonshine-laravel.com" target="_blank">
<img src="https://github.com/lee-to/moonshine-tree-resource/blob/master/art/screenshot.png">
</a>
</p>

### Requirements

- MoonShine v2.0+

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
            CategoryTreePage::make($this->title()),
            FormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            DetailPage::make(__('moonshine::ui.show')),
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

    // ...
}
```

And add component

```php
namespace App\MoonShine\Pages;

use Leeto\MoonShineTree\View\Components\TreeComponent;
use MoonShine\Pages\Crud\IndexPage;

class CategoryTreePage extends IndexPage
{
    protected function mainLayer(): array
    {
        return [
            ...$this->actionButtons(),
            TreeComponent::make($this->getResource()),
        ];
    }
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
