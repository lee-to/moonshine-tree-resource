## MoonShine Sortable Tree Resource

A modern, responsive tree component for MoonShine with drag & drop sorting, compact mode, and customizable content display.

<p align="center">
<a href="https://moonshine-laravel.com" target="_blank">
<img src="https://github.com/lee-to/moonshine-tree-resource/blob/2.x/art/screenshot.png">
</a>
</p>

## Features

- 🚀 **Drag & Drop Sorting** - Sort and reorder items with ease
- 📱 **Mobile First Design** - Responsive and touch-friendly
- 🎯 **Compact Mode** - Dense layout for data-heavy interfaces
- 🎨 **Customizable Content** - Flexible title, badge, and description
- 🌓 **Dark Mode Support** - Integrates seamlessly with MoonShine themes
- ⚡ **Optimized Performance** - Lightweight CSS with MoonShine tokens

### Requirements

- MoonShine v4.0+

## Compatibility

| MoonShine | TreeResource |
|-----------|--------------|
| 2.0+      | 1.0+         |
| 3.0+      | 2.0+         |
| 4.0+      | 3.0+         |

### Installation

```shell
composer require lee-to/moonshine-tree-resource
```

```shell
php artisan vendor:publish --tag=moonshine-tree-assets
```

## Quick Start

Extend the TreeResource class instead of the base ModelResource

```php
use Leeto\MoonShineTree\Resources\TreeResource;

class CategoryResource extends TreeResource
{
    // Required properties
    protected string $column = 'title';

    protected string $sortColumn = 'sorting';

    // ...

    // Required methods
    public function treeKey(): ?string
    {
        return 'parent_id'; // Foreign key for parent-child relationship
    }

    public function sortKey(): string
    {
        return 'sorting'; // Column for sorting
    }
}
```

And add a component to `IndexPage`

```php
use Leeto\MoonShineTree\View\Components\TreeComponent;

protected function mainLayer(): array
    {
        return [
            ...$this->getPageButtons(),
            TreeComponent::make($this->getResource()),
        ];
    }
````

Or override list component

```php
use Leeto\MoonShineTree\View\Components\TreeComponent;

public function modifyListComponent(ComponentContract $component): ComponentContract
{
    return TreeComponent::make($this->getResource());
}
```

## Custom Content Display

```php
public function treeItemTitle(Model $item): string
{
    return $item->{$this->getColumn()};
}

public function treeItemBadgeText(Model $item): string
{
    return $item->products_count ?? ''; // Show product count as badge
}

public function treeItemBadgeColor(Model $item): string
{
    return Color::PRIMARY; // Use Color enum for better type safety
}

public function treeItemDescription(Model $item): string
{
    return $item->short_description ?? ''; // Additional description
}
```

## Configuration Options

```php
public function sortable(): bool
{
    return true; // Enable/disable drag & drop sorting
}

public function wrappable(): bool
{
    return true; // Enable/disable expand/collapse functionality
}

public function compactTree(): bool
{
    return false; // Enable compact mode for dense layouts
}
```
