
# WordPress Router

## Installation

```bash
composer require lumenpress/wp-router
```

Register the provider in `bootstrap/app.php`:

```php
$app->register(LumenPress\WordPressRouter\ServiceProvider::class);
```

It should be noted that Laravel must also be registered in the `bootstrap/app.php` file, otherwise it will not work properly.

## Usage

We must first get an instance of the WordPress router.

```php
$router = app('wp.router');
```

Or use the router service container as a facade.

```php
use LumenPress\WordPressRouter\Facade\Route;
```

### Routing

```php
$router->is($condition, $callback);
$router->get($condition, $callback);
$router->post($condition, $callback);
$router->put($condition, $callback);
$router->patch($condition, $callback);
$router->delete($condition, $callback);
$router->options($condition, $callback);

$router->group([
        'middleware' => 'auth', 
        'namespace' => 'App\Http\Controllers'
    ], function ($router) {
        //
});
```

As a facade.

```php
use LumenPress\WordPressRouter\Facade\Route;

Route::is($condition, $callback);
Route::get($condition, $callback);
Route::post($condition, $callback);
Route::put($condition, $callback);
Route::patch($condition, $callback);
Route::delete($condition, $callback);
Route::options($condition, $callback);

Route::middleware('auth')
    ->namespace('App\Http\Controllers')
    ->group(function () {
    //
});
```

## Conditions

**Route Parameters**

```php
Route::is(string $condition, $callback);
Route::is([$condition => int|string|array $args], $callback);
```

### singular

Query Condition

| function | theme file |
|--|--|
| is_singular() | singular.php |
| is_singular($postType) | single-`$postType`.php |

Route Condition

- `singular`
- `['singular' => string|array $postType]`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
Route::is('singular', function (LumenPress\Nimble\Models\Page $post) {});

// Single
Route::is(['singular' => 'book'], $callback);

// Multiple
Route::is(['singular' => ['newspaper', 'book']], $callback);
```

### template

Query Condition

| function | theme file |
|--|--|
| is_page_template($template) | `$template`.php |

Route Condition

- `['template' => string|array $template]`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post`;

```php
Route::is(['template' => 'home'], function (LumenPress\Nimble\Models\Post $post) {});
// Multiple
Route::is(['template' => 'contact', 'about'], $callback);
```

### page

Query Condition

| function | theme file |
|--|--|
| is_page() | page.php |
| is_page($id) | page-`$id`.php |
| is_page($slug) | page-`$slug`.php |

Route Condition

- `page`
- `['page' => int|string|array $page]`

Callback Arguments

- `LumenPress\Nimble\Models\Page $page` `optional`;

```php
Route::is('page', function (LumenPress\Nimble\Models\Page $page) {});

// By id
Route::is(['page' => 2], $callback);

// By slug
Route::is(['page' => 'sample-page'], $callback);

// Multiple
Route::is(['page' => ['about', 'contact']], $callback);
```

### single

Query Condition

| function | theme file |
|--|--|
| is_single() | single.php |
| is_single($id) | single-`$id`.php |

Route Condition

- `single`
- `['single' => int|string|array $post]`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
Route::is('single', function (LumenPress\Nimble\Models\Post $post) {});

// By id
Route::is(['single' => 1], $callback);

// By slug
Route::is(['single' => 'hello-world'], $callback);

// Multiple
Route::is(['single' => ['foo', 'bar']], $callback);
```

### attachment

Query Condition

| function | theme file |
|--|--|
| is_attachment() | attachment.php |

Route Condition

- `attachment`

Callback Arguments

- `LumenPress\Nimble\Models\Attachment $attachment` `optional`;

```php
Route::is('attachment', function (LumenPress\Nimble\Models\Attachment $attachment) {});
```

### embed

Query Condition

| function | theme file |
|--|--|
| is_embed() | embed.php |

Route Condition

- `embed`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
Route::is('embed', function (LumenPress\Nimble\Models\Post $post) {});
```

### archive

Query Condition

| function | theme file |
|--|--|
| is_archive() | archive.php |
| is_post_type_archive($postType) | archive-`$postType`.php |

Route Condition

- `archive`
- `['archive' => string|array $postType]`

Callback Arguments

- `string $postType` `optional`;

```php
Route::is('archive', $callback);

Route::is(['archive' => 'book'], $callback);

Route::is(['archive' => ['newspaper', 'book']], function ($postType) {});
```

### tax

Query Condition

| function | theme file |
|--|--|
| is_tax() | taxonomy.php |
| is_tax($taxonomy) | taxonomy-`$taxonomy`.php |
| is_tax($taxonomy, $term) | taxonomy-`$taxonomy`-`$term`.php |

Route Condition

- `tax`
- `['tax' => string|array $taxonomy]`
- `['tax' => ...[string|array $taxonomy, int|string|array string|array $term]]`

Callback Arguments

- `LumenPress\Nimble\Models\Taxonomy|string $taxonomy` `optional`;

```php
Route::is('tax', function ($taxonomy) {});

Route::is(['tax' => 'channel'], $callback);
// Multiple
Route::is(['tax' => [['channel', 'BBC1']]], $callback);
```

### category

Query Condition

| function | theme file |
|--|--|
| is_category() | category.php |
| is_category($id) | category-`$id`.php |
| is_category($slug) | category-`$slug`.php |

Route Condition

- `category`
- `['category' => string|array $category]`

Callback Arguments

- `LumenPress\Nimble\Models\Category $category` `optional`;

```php
Route::is('category', function (LumenPress\Nimble\Models\Category $category) {});

Route::is(['category' => 9], $callback);
Route::is(['category' => 'blue-cheese'], $callback);
Route::is(['category' => 'Stinky Cheeses'], $callback);

Route::is(['category' => [9, 'blue-cheese', 'Stinky Cheeses']], $callback);
```

### tag

Query Condition

| function | theme file |
|--|--|
| is_tag() | tag.php |
| is_tag($id) | tag-`$id`.php |
| is_tag($slug) | tag-`$slug`.php |

Route Condition

- `tag`
- `['tag' => string|array $tag]`

Callback Arguments

- `LumenPress\Nimble\Models\Tag $tag` `optional`;

```php
Route::is('tag', function (LumenPress\Nimble\Models\Tag $tag) {});

Route::is(['tag' => 30], $callback);
Route::is(['tag' => 'extreme'], $callback);
Route::is(['tag' => 'mild'], $callback);

Route::is(['tag' => [30, 'mild', 'extreme']], $callback);
```

### author

Query Condition

| function | theme file |
|--|--|
| is_author() | author.php |
| is_author($id) | author-`$id`.php |
| is_author($nicename) | author-`$nicename`.php |

Route Condition

- `author`
- `['author' => int|string|array $author]`

Callback Arguments

- `LumenPress\Nimble\Models\User $author` `optional`;

```php
Route::is('author', function (LumenPress\Nimble\Models\User $author) {});

Route::is(['author' => 4], $callback);
Route::is(['author' => 'john-jones'], $callback);
Route::is(['author' => 'Vivian'], $callback);

Route::is(['author' => [4, 'john-jones', 'Vivian']], $callback);
```

### date

Query Condition

| function | theme file |
|--|--|
| is_date() | date.php |

Route Condition

- `date`

Callback Arguments

- `$year` `optional`;
- `$month` `optional`;
- `$day` `optional`;

```php
Route::is('date', function ($year = null, $month = null, $day = null) {});
```

### home

Query Condition

| function | theme file |
|--|--|
| is_home() | home.php |

Route Condition

- `home`

```php
Route::is('home', $callback);
```

### front_page

Query Condition

| function | theme file |
|--|--|
| is_front_page() | front_page.php |

Route Condition

- `front_page`

```php
Route::is('front_page', $callback);
```

### search

Query Condition

| function | theme file |
|--|--|
| is_search() | search.php |

Route Condition

- `search`

```php
Route::is('search', $callback);
```

### 404

Query Condition

| function | theme file |
|--|--|
| is_404() | 404.php |

Route Condition

- `404`

```php
Route::is('404', $callback);
```

## Custom Route Condition

coming soon
