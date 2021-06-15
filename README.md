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
php artisan vendor:publish --provider="Kblais\QueryFilter\QueryFilterServiceProvider" --tag="query-filter-config"
```

This is the contents of the published config file:

```php
return [
    'default-filters-source' => null,
];
```

## Usage

A QueryFilter is a class to apply, based on an array or a Request, multiple conditions.

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
// From an array...
$filterInput = [
    'title' => 'Les Trois Mousquetaires',
];

$posts = Post::filter(PostFilter::make($filterInput))->get();

// ...Or in a controller action
public function index(PostFilter $filter)
{
    // Filter is automatically populated with Request data when injected
    return Post::filter($filter)->get();
}
```

If your filter parameters are always placed in an array key (for example `filters`), you can define the `default-filters-source` config key in the config file, or add a `protected string $source = 'filters'` in your QueryFilter.

## Frequent issues

### `Call to an undefined method App\QueryFilters\YourFilter::anEloquentScope().` with PHPStan

To fix this error message, add the following DocBlock to your filter:

```
/**
 * @mixin \App\Models\ModelOfYourScope
 */
```

## Testing

The test suite is composed of 3 tests: PHPCsFixer (coding style), PHPStan (static analysis) and PHPUnit (unit tests).

You can run all these tests running the following command:

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
