# WordPress Routing

[![Build Status](https://travis-ci.org/lumenpress/routing.svg?branch=master)](https://travis-ci.org/lumenpress/routing) [![Total Downloads](https://poser.pugx.org/lumenpress/routing/downloads)](https://packagist.org/packages/lumenpress/routing) [![Latest Stable Version](https://poser.pugx.org/lumenpress/routing/v/stable)](https://packagist.org/packages/lumenpress/routing)

The routing needs to be used with [lumenpress/laravel](https://github.com/lumenpress/laravel) or [lumenpress/lumen](https://github.com/lumenpress/lumen).

## Installation

```bash
composer require lumenpress/routing
```

Register the provider in `bootstrap/app.php`:

```php
$app->register(LumenPress\Routing\ServiceProvider::class);
```

It should be noted that Laravel must also be registered in the `bootstrap/app.php` file, otherwise it will not work properly.

## Usage

We must first get an instance of the WordPress router.

```php
$router = app('wp.router');
```

Or use the router service container as a facade.

```php
use LumenPress\Routing\Facades\Route;
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
use LumenPress\Routing\Facades\Route;

Route::is($condition, $callback);
Route::get($condition, $callback);
Route::post($condition, $callback);
Route::put($condition, $callback);
Route::patch($condition, $callback);
Route::delete($condition, $callback);
Route::options($condition, $callback);

Route::group([
        'middleware' => 'auth', 
        'namespace' => 'App\Http\Controllers'
    ],function () {
    //
});
```

## Conditions

**Route Parameters**

```php
Route::is(string $condition, $callback);
Route::is([$condition => int|string|array $args], $callback);
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
// register templates
LumenPress\Nimble\Models\Post::registerTemplate([
    'home' => [
        'name' => 'Home Page'
    ],
    'contact' => [
        'name' => 'Contact Us'
    ],
    'about' => [
        'name' => 'Contact Us'
    ],
]);

Route::is(['template' => 'home'], function (\LumenPress\Nimble\Models\Post $post) {});

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

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
// page.php
Route::is('page', function (\LumenPress\Nimble\Models\Post $post) {});

// page-2.php
Route::is(['page' => 2], $callback);

// page-sample-page.php
Route::is(['page' => 'sample-page'], $callback);

// page-about.php or page-contact.php
Route::is(['page' => ['about', 'contact']], $callback);

// By path
Route::is(['page' => 'about/company'], $callback);
Route::is(['page' => 'about/staff'], $callback);
```

### single

Query Condition

| function | theme file |
|--|--|
| is_single() | single.php |
| is_singular($posttype) | single-`$posttype`.php |
| is_singular($posttype) && is_single($slug) | single-`$posttype`-`$slug`.php |

Route Condition

- `single`
- `['single' => int|string|array $post]`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
// single.php
Route::is('single', function (\LumenPress\Nimble\Models\Post $post) {});

// query by post id
Route::is(['single' => 1], $callback);

// single-book.php
Route::is(['single' => 'book'], $callback);

// single-book.php or single-newspaper.php
Route::is(['single' => ['book', 'newspaper']], $callback);

// single-book-foo.php 
// or single-book-bar.php 
// or single-newspaper-foo.php 
// or single-newspaper-bar.php
$single = [
    // $post_type,  $slug
    ['book',        'foo'],
    ['book',        'bar'],
    ['newspaper',   'foo'],
    ['newspaper',   'bar'],
];
Route::is(['single' => $single], $callback);
```

### singular

Query Condition

| function | theme file |
|--|--|
| is_singular() | singular.php |
| is_singular($posttype) | single-`$posttype`.php |

Route Condition

- `singular`
- `['singular' => string|array $posttype]`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
// singular.php
Route::is('singular', function (\LumenPress\Nimble\Models\Page $post) {});

// single-book.php
Route::is(['singular' => 'book'], $callback);

// single-book.php or single-newspaper.php
Route::is(['singular' => ['newspaper', 'book']], $callback);
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
// attachment.php
Route::is('attachment', $callback);
```

### embed

Since 4.5

Query Condition

| function | theme file |
|--|--|
| is_embed() | embed.php |

Route Condition

- `embed`

Callback Arguments

- `LumenPress\Nimble\Models\Post $post` `optional`;

```php
// embed.php
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
// archive.php
Route::is('archive', $callback);

// archive-book.php
Route::is(['archive' => 'book'], $callback);

// archive-newspaper.php or archive-book.php
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

- `LumenPress\Nimble\Models\Taxonomy $taxonomy` `optional`;

```php
// taxonomy.php
Route::is('tax', function (\LumenPress\Nimble\Models\Taxonomy $taxonomy) {});

// taxonomy-channel.php
Route::is(['tax' => 'channel'], $callback);

// taxonomy-channel-bbc1.php
Route::is(['tax' => [['channel', 'bbc1']]], $callback);
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
// category.php
Route::is('category', function (\LumenPress\Nimble\Models\Category $category) {});

// category-9.php
Route::is(['category' => 9], $callback);

// category-news.php
Route::is(['category' => 'news'], $callback);

// by category name
Route::is(['category' => 'Stinky Cheeses'], $callback);

// by id, slug, name...
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
// tag.php
Route::is('tag', function (\LumenPress\Nimble\Models\Tag $tag) {});

// tag-30.php
Route::is(['tag' => 30], $callback);

// tag-extreme.php
Route::is(['tag' => 'extreme'], $callback);
// tag-mild.php
Route::is(['tag' => 'mild'], $callback);

// by id, slug, name...
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
// author.php
Route::is('author', function (\LumenPress\Nimble\Models\User $user) {});

// author-4.php
Route::is(['author' => 4], $callback);

// author-john-jones.php
Route::is(['author' => 'john-jones'], $callback);

// by display name
Route::is(['author' => 'Vivian'], $callback);

// by mixed
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
// date.php
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
// home.php
Route::is('home', $callback);
```

### front

Query Condition

| function | theme file |
|--|--|
| is_front_page() | front_page.php |

Route Condition

- `front`

```php
// front_page.php
Route::is('front', $callback);
```

### search

Query Condition

| function | theme file |
|--|--|
| is_search() | search.php |

Route Condition

- `search`

```php
// search.php
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
// 404.php
Route::is('404', $callback);
```

## Custom Route Condition

```php
Route::addRouteCondition('author.role', function ($role) {
    if (! is_author()) {
        return false;
    }

    $author = get_queried_object();

    return $role == $author->roles[0];
});

Route::is(['author.role' => 'administrator'], $callback);
```
