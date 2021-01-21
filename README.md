# QueryFilter [![Build Status](https://travis-ci.org/kblais/query-filter.svg?branch=master)](https://travis-ci.org/kblais/query-filter)

Easily create filters for your Eloquent model.

Based on [Jeffray Way's Laracast tutorial](https://github.com/laracasts/Dedicated-Query-String-Filtering/).

## Installation

You can install the package via composer:

```bash
composer require kblais/query-filter
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Kblais\QueryFilter\QueryFilterServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    'default-filters-source' => '',
];
```

## Usage

A query filter is a class where you explain Eloquent it should filter models based on the input.

You can call any Eloquent method directly from filter methods.

```php
use Kblais\QueryFilter\QueryFilter;

class PostFilter extends QueryFilter
{
    public function title($value)
    {
        return $this->where('foo', 'bar');
    }

    public function author($value)
    {
        return $this->whereHas('author', function ($builder) use ($value) {
            $this->where('name', $value);
        });
    }
}
```

To allow a model to use query filters, you have to add the `Filterable` trait on your model.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kblais\QueryFilter\Filterable;

class Post extends Model
{
    use Filterable;
}
```

You can then use the `filter()` scope from anywhere:

```php
// From an array:
$filterInput = [
    'title' => 'Les Trois Mousquetaires',
];

$posts = Post::filter(PostFilter::make($filterInput))->get();

// In a controller action:
public function index(PostFilter $filter)
{
    // Filter is automatically populated with Request data when injected
    return Post::filter($filter)->get();
}
```

## Testing

```
composer tests
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

- Follow the PSR-2 Coding Standard. Use PHP-CS-Fixer to apply the conventions.
- Add tests for the features you add and bugs you discover.

## Credits

- [Killian BLAIS](https://github.com/kblais)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
