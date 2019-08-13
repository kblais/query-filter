# QueryFilter [![Build Status](https://travis-ci.org/kblais/query-filter.svg?branch=master)](https://travis-ci.org/kblais/query-filter)

Easily create filters for your Eloquent model.

Based on [Jeffray Way's Laracast tutorial](https://github.com/laracasts/Dedicated-Query-String-Filtering/).

## Installation

You can install this package via Composer with this command:

```
composer require kblais/query-filter
```

## Usage

- Create your model filters, for exemple in a `App\Http\Filters` namespace :

```php
<?php

namespace App\Http\Filters;

use Kblais\QueryFilter\QueryFilter;

class MyModelFilter extends QueryFilter
{
    public function foo($value)
    {
        return $this->builder->where('foo', 'bar');
    }
}
```

- Then, add the `FilterableTrait` on your model to allow the use of `MyModel::filter()` :

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kblais\QueryFilter\FilterableTrait;

class MyClass extends Model
{
    use FilterableTrait;
}
```

- Finally, you can use the `MyModel::filter()` method in your controller :

```php
<?php

namespace App\Http\Controllers;

use App\Http\Filters\MyModelFilter;
use App\MyModel;
use Kblais\QueryFilter\FilterableTrait;

class MyController extends Controller
{
    public function index(MyModelFilter $filters)
    {
        $data = MyModel::filter($filters)->get();

        return response()->json(compact('data'));
    }
}
```

## Contributing

- Follow the PSR-2 Coding Standard. Use PHP-CS-Fixer to apply the conventions.
- Add tests !
- Create feature branches
- One pull request per feature
