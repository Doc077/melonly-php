<div style="display: flex; align-items: center; margin-bottom: 18px;">
    <img src="public/logo.png" width="68">
    <span style="font-size: 32px; margin-left: 12px;">Melonly</span>
</div>

## Melonly PHP Framework

Melonly is a fast, modern web application development framework for PHP. It makes easy to create secure and fast web applications with nice developer experience.

Melonly documentation is available on the [official site](https://melonly.dev).

### Requirements

- PHP 8.1+
- [Composer](https://getcomposer.org) installed
- PDO PHP Extension
- GD or Imagick PHP Extension


## Installation

To create a fresh Melonly project use the ```composer``` installer:

```shell
> cd <path-to-directory>

> composer create-project melonly/melonly <app-name>

> cd <app-name>
```

Once your application has been created you can run it locally:

```shell
> php melon server
```

Optionally if you'll be using Node.js or React/Sass frontend libraries, run ```npm install``` command:

```shell
> npm install
```


## Running Application & Development

To run your application on local environment, use the command line:

```shell
> php melon server
```

Your application will be available on ```localhost:5000``` by default. If some other program uses this port, you can change it to your own:

```shell
# Run server on port 8000

> php melon server 8000
```


## Console Interface

### Useful Melonly CLI Commands

Melonly ships with Melon CLI - Terminal mode client for development. It has many useful commands. Below You can test some of them:

```shell
# Display information about framework version

> php melon version
```

```shell
# Create new controller file

> php melon new:controller PostController
```

```shell
# Create new ORM model

> php melon new:model Post
```

```shell
# Create custom CLI command

> php melon new:command YourCustomCommand
```

```shell
# Get list of all available commands

> php melon info
```


## Basic Routing

To register application routes, edit ```routing/routes.php``` file.

```php
Route::get('/my-route', function (Request $request, Response $response): void {
    $response->send('My first route');
});
```

As you can see, you can supply a simple callback with injected request & response objects to return some response.
Enter the ```localhost:5000/my-route``` route and look for the result.


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
<div>Price: {{ $price * 4.2 }} USD</div>
```


### Templates

Melonly ships with a convinient templating engine called Fruity. To see how it works look at this example:

```html
<div>
    [foreach $posts as $post]
        <h2>{{ $post->title }}</h2>

        <p>{{ $post->content }}</p>
    [endforeach]
</div>
```

As you can see this is a very simple yet powerful engine. It's also a lot more clean compared to plain PHP templates.


## Database Queries

Handling databases in web applications cannot be easier than with Melonly. To execute raw SQL query, use ```DB``` interface.

```php
use Melonly\Database\DB;

$name = DB::query('SELECT `name` FROM `users` WHERE `id` = 1');
```

Due the fact Melonly is a MVC framework, it follows the Model pattern. Each table in your database can have corresponding 'Model'.

To create a model, use CLI command:

```shell
> php melon new:model ModelName
```

For example:

```shell
> php melon new:model Post
```

It will create ```models/Post.php``` model file with the following structure:

```php
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

Column data typing is done using the ```Column``` attribute. It is not required but considered a good practice.

Then if your database contains ```posts``` table, you can retrieve data from that table with model class. Melonly uses plural '-s' suffix by default to retrieve tables but it can be overwritten by setting ```protected $table``` property in a model.

By chaining methods it is possible to create complex ```where``` clauses. Some of available methods are: ```where```, ```orWhere```, ```orderBy```. Data returned by the ```fetch()``` method is type of ```vector``` (in case of single result it is an instance of ```Melonly\Database\Record```).

```php
use App\Models\Post;

$post = Post::where('id', '=', 1)->orWhere('title', 'like', '%PHP%')->fetch();

$title = $post->title;
```

As you can see, dealing with DB data is super easy with Melonly.


## Documentation

If you want to dig deeper and learn some advanced Melonly features, you may visit the official [documentation](https://melonly.dev/docs).

---

## License

Melonly is licensed under the [MIT license](LICENSE).

Author: Doc077
