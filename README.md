<img src="public/logo.png" width="68">

# Melonly

## Melonly PHP Framework

Melonly is a fast, modern web application development framework for PHP. It makes easy to create secure and fast web applications with nice developer experience.

COMING SOON: Melonly documentation is available on the [official site](https://melonly.dev).

### Requirements

- PHP 8.1+
- [Composer](https://getcomposer.org) installed
- PDO PHP Extension
- cURL PHP Extension
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

> php melon -v
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
use Melonly\Routing\Facades\Route;

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
        'username' => Auth::user()->name,
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
<div class="content">
    [foreach $posts as $post]
        <h2>{{ $post->title }}</h2>

        <p>{{ $post->content }}</p>
    [endforeach]
</div>
```

As you can see Fruity is very simple yet powerful engine. It's also a lot more clean compared to plain PHP templates.

However if you don't like these templates, Melonly supports [Twig](https://twig.symfony.com) engine for handling views. After setting the ```engine``` option in ```config/view.php``` file to ```Twig```, you can use Twig templates in your app (note that view file extensions would be ```.html.twig``` then).

```html
Example Twig template

{% for item in array %}
    <li>{{ item }}</li>
{% endfor %}
```


## Database Queries

Handling databases in web applications cannot be easier than with Melonly. To execute raw SQL query, use ```DB``` interface.

```php
use Melonly\Database\Facades\DB;

$name = DB::query('select `name` from `users` where `id` = 1');
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

It will create ```src/Models/Post.php``` model file with the following structure:

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


## Validation

Request data validation with Melonly is super easy. Look how it works:

```php
use Melonly\Validation\Facades\Validate;

Validate::check([
    'username' => ['required', 'min:3', 'max:30', 'alphanumeric'],
    'email' => ['email', 'max:30'],
    'age' => ['int'],
]);
```

Available validation rules are listed here:

- alphanumeric
- bool
- domain
- email
- file
- float
- int
- ip
- max:{length}
- min:{length}
- number
- regex:{pattern}
- required
- string
- unique:{table}
- url


## Making HTTP Requests

Many times your application need to make some kind of request, for example for retrieving API data.

```php
use Melonly\Http\Http;

// GET Request
$data = Http::get('https://my-api');

// POST Request
Http::post('https://my-api', [
    'id' => $userId,
]);
```


## Helpers

Melonly offers many useful global functions (helpers) used by Melonly itself. Feel free to use them in your code if you find it convinient.

### String Helpers

```php
use Melonly\Support\Helpers\Str;

$string = 'Melonly helpers';

// Uppercase string
$string = Str::uppercase($string);

// Replace occurences
$string = Str::replace(' ', '_', $string); // Output: 'Melonly_helpers'

// PascalCase string
$string = Str::pascalCase($string); // Output: 'MelonlyHelpers'
```


### Time Manipulation Helpers

```php
use Melonly\Support\Helpers\Time;

$date = Time::now()->isoFormat('Y_MM_D'); // Output: 2022_01_27
```


### UUID Generation

```php
use Melonly\Support\Helpers\Uuid;

// Generate unique ID (UUID v4)
$id = Uuid::v4();
```


### JSON Helpers

```php
use Melonly\Support\Helpers\Json;

$data = ['id' => 145];

$json = Json::encode($data); // Return JSON object
```


### Other Helpers

```php
dd('Some data'); // Get information about variable or some value and exit

throwIf($condition, new Exception); // Throw an exception if condition is true

redirect('/login'); // Redirect user to given URL

redirectBack(); // Redirect user to previous location

$vector = vector(1, 2, 3); // Create a new vector with provided values

$message = trans('app.welcome'); // Get language translation
```


## Encryption & Hashing

On backend you'll often need to encrypt or hash some data, e.g. user password in database. Look how it works:

```php
use Melonly\Encryption\Facades\Hash;

$passwordHash = Hash::hash($request->get('password'));

// Compare provided password with database hash
$isPasswordCorrect = Hash::equals($request->get('password'), $user->password);
```


## WebSockets & Broadcasting

Modern web applications often need WebSocket connection. Melonly supports two popular broadcasting drivers out of the box: [Pusher](https://pusher.com) and [Ably](https://ably.com). You can configure the driver in ```.env``` file:

```
WEBSOCKET_DRIVER=pusher|ably
```

Then provide your Pusher/Ably account credentials:

```
PUSHER_KEY=
PUSHER_SECRET_KEY=
PUSHER_APP_ID=
PUSHER_CLUSTER=mt1

ABLY_KEY=
```

After configuration you may create your first broadcasts. Look how it works:

```php
use Melonly\Broadcasting\Facades\WebSocket;

WebSocket::broadcast('channel-name', 'EventName', $data);
```


## Documentation

If you want to dig deeper and learn some advanced Melonly features, you may visit the official [documentation](https://melonly.dev/docs).


## License

Melonly is licensed under the [MIT license](LICENSE).

Author: Doc077 (dom.rajkowski@gmail.com)
