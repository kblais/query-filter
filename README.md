# QueryFilter [![Build Status](https://travis-ci.org/kblais/query-filter.svg?branch=master)](https://travis-ci.org/kblais/query-filter)

Easily create filters for your Eloquent model.

Based on [Jeffray Way's Laracast tutorial](https://github.com/laracasts/Dedicated-Query-String-Filtering/).

## Installation

The library is currently not available on Composer, so you have to declare it manually in your `composer.json`.

To do this, add the following in your `composer.json` :

```json
{
    "require" : {
        "kblais/query-filter": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kblais/query-filter"
        }
    ]
}
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
