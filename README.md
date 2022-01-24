# Melonly PHP Framework

Melonly is a fast, modern web application development framework for PHP. It makes easy to create secure and fast web applications with nice developer experience.

Melonly is available to download from the [official site](https://melonly.dev).


## Documentation

If you want to dig deeper and learn some advanced Melonly features, you may visit the official [documentation](https://melonly.dev/docs).


## Download

To start a basic project, You can clone the repository:

```
> cd <path-to-your-app>

> git clone https://github.com/Doc077/melonly.git

> cd melonly
> npm install
```

Then remove ```.git``` folder from the main directory and start your app.


## Run Application & Development

To run your application on local environment, use the command line:

```
# Run the Melonly development server

> php melon server
```

Your application will be available on ```localhost:5000```.


## Console Interface

### Useful Melonly CLI Commands

Melonly ships with Melon CLI - Terminal mode client for development. It has many useful commands. Below You can test some of them:

```
# Display information about framework version

> php melon version
```

```
# Create new controller file

> php melon new:controller PostController
```

```
# Create new ORM model

> php melon new:model Post
```

```
# Run unit tests

> php melon test
```


## Basic Routing

To register application routes, edit ```routes/web.php``` file.

```php
Route::get('/my-path', function (Request $request, Response $response): void {
    $response->send('My first route');
});
```

As you can see, you can supply a simple callback with injected request & response objects to return some response.
Enter the ```localhost:5000/my-path``` route and look for the result.


## Views

Modern applications deal with user interfaces. Therefore Melonly handles special HTML templates which help You building applications in pleasant way.
View files / templates are located in ```frontend/views``` directory. You can display a view returning it from the response, using the "dot" syntax.


### Displaying a View

```php
Route::get('/login', function (Request $request, Response $response): void {
    $response->view('pages.login'); // Refers to pages/login.html file
});
```


### Passing variables

To display variable in a view, you can pass an array with supplied variable names and values.

```php
Route::get('/dashboard', function (Request $request, Response $response): void {
    $response->view('pages.dashboard', [
        'username' => Auth::user()->name
    ]);
});
```

Then you may use it in the template:

```html
<h1>Welcome, {{ $username }}</h1>
```

Melonly templates allow us to write simple PHP expressions directly in HTML.

```html
<div>Price: {{ 2 + 2 * 6 }} USD</div>
```


## Database Queries

Handling databases in web applications cannot be easier than with Melonly. To execute raw SQL query, use ```DB``` interface.

```php
use Melonly\Database\DB;

$name = DB::query('SELECT `name` FROM `users` WHERE `id` = 1');
```

Due fact that Melonly is a MVC framework, it includes Models pattern. Each table in your database can have corresponding 'Model'.

To create a model, use CLI command:

```
> php melon new:model ModelName
```

For example:

```
> php melon new:model Post
```

It will create ```models/Post.php``` model file with this structure:

```php
<?php

namespace App\Models;

use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\PrimaryKey;
use Melonly\Database\Model;

class Post extends Model {
    #[PrimaryKey]
    public $id;

    #[Column(type: 'string')]
    public $name;

    #[Column(type: 'datetime', nullable: true)]
    public $created_at;
}
```

Column data typing is done using ```Column``` attribute.

If your database contains ```posts``` table, you can retrieve data from that table with model class.

By chaining methods it is possible to create ```where``` clauses. Some of available methods are: ```where```, ```orWhere```, ```orderBy```. Data returned by the ```fetch()``` method is type of ```vector``` (in case of single result it is an instance of Record).

```php
use App\Models\Post;

$post = Post::where('id', '=', 1)->orWhere('title', 'like', '%PHP%')->fetch();

$title = $post->title;
```

As you can see, dealing with DB data is super easy with Melonly.

---

## License

Melonly is licensed under the [MIT license](LICENSE).

Author: Doc077
