<img src="public/logo.png" width="68">

## Melonly PHP Framework

[![PHP Version Require](https://poser.pugx.org/melonly/melonly/require/php)](https://packagist.org/packages/melonly/melonly)
[![Latest Stable Version](https://poser.pugx.org/melonly/melonly/v)](https://packagist.org/packages/melonly/melonly)
[![Total Downloads](https://poser.pugx.org/melonly/melonly/downloads)](https://packagist.org/packages/melonly/melonly)
[![License](https://poser.pugx.org/melonly/melonly/license)](https://packagist.org/packages/melonly/melonly)

Melonly is a fast and modern web application development framework for PHP. It makes easy to create secure and fast web applications with awesome developer experience.

*COMING SOON*: Melonly documentation will be available on the [official site](website).

**Table of Contents | Documentation**

- [Melonly PHP Framework](#melonly-php-framework)
- [Requirements](#requirements)
- [Installation](#installation)
- [Running Application](#running-application)
- [App Directory Structure](#app-directory-structure)
  - [Root Directory](#root-directory)
    - [`/config`](#config)
    - [`/database`](#database)
    - [`/frontend`](#frontend)
    - [`/melonly`](#melonly)
    - [`/public`](#public)
    - [`/src`](#src)
    - [`/storage`](#storage)
    - [`/tests`](#tests)
    - [`/vendor`](#vendor)
  - [`src/` Directory](#src-directory)
    - [`/Controllers`](#controllers)
    - [`/Exceptions`](#exceptions)
    - [`/Middleware`](#middleware)
    - [`/Models`](#models)
    - [`/Services`](#services)
    - [`/Commands`](#commands)
    - [`/Emails`](#emails)
- [Command Line Interface](#command-line-interface)
  - [Useful Built-In Commands](#useful-built-in-commands)
  - [Custom Commands](#custom-commands)
- [Routing](#routing)
  - [Basic Routing with Callbacks](#basic-routing-with-callbacks)
  - [Routing Parameters](#routing-parameters)
  - [Controllers](#controllers-1)
    - [Single-Action Controllers](#single-action-controllers)
  - [Middleware](#middleware-1)
- [Views](#views)
  - [Displaying a View](#displaying-a-view)
  - [Passing Variables](#passing-variables)
  - [Templates](#templates)
  - [Twig Templates](#twig-templates)
- [Database](#database-1)
  - [Query Builder](#query-builder)
  - [Raw SQL Queries](#raw-sql-queries)
  - [Models](#models-1)
    - [Retrieving Data](#retrieving-data)
    - [Creating Records](#creating-records)
  - [Migrations](#migrations)
- [Authentication](#authentication)
- [Session](#session)
- [Validation](#validation)
- [Making HTTP Requests](#making-http-requests)
- [Files](#files)
  - [Image files](#image-files)
- [Helpers](#helpers)
  - [String Manipulation](#string-manipulation)
  - [Date and Time Manipulation](#date-and-time-manipulation)
  - [UUID Generation](#uuid-generation)
  - [JSON](#json)
  - [Other Helpers](#other-helpers)
- [Encryption and Hashing](#encryption-and-hashing)
- [Sending Emails](#sending-emails)
- [Frontend Frameworks (React and Vue)](#frontend-frameworks-react-and-vue)
- [WebSockets and Broadcasting](#websockets-and-broadcasting)
- [Testing](#testing)
- [Deployment](#deployment)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)


## Requirements

- PHP 8.1+
- [Composer](https://getcomposer.org) installed
- PDO PHP Extension
- cURL PHP Extension
- Fileinfo PHP Extension
- Image Processing PHP Extension
- OpenSSL PHP Extension


## Installation

To create a fresh Melonly project use the `composer` installer:

```shell
> composer create-project melonly/melonly <app-name>

> cd <app-name>
```


## Running Application

Once your application project has been created you can run it on the local server:

```shell
> php melon server
```

Your application will be available on `localhost:5000` by default. If this port is already used by some application, you can change it to another, like `3000` or `8000`:

```shell
> php melon server --port=8000
```


## App Directory Structure

Default Melonly application structure consists of several main folders:


### Root Directory

#### `/config`

This directory contains configuration files for your application. You can create custom file inside this directory and refer to it using `config($file, $key)` helper.


#### `/database`

In this directory are created database migrations. You can store `sqlite` database here.


#### `/frontend`

There are placed all files related to frontend side like views or uncompiled styles/scripts.


#### `/melonly`

This folder contains framework files. You don't need to change anything there.


#### `/public`

This is the only directory visible to users. It prevents from direct access to source code. `public` folder contains `.htaccess` and `index.php` files. This is where you should put client side things like compiled styles, JS scripts and images.


#### `/src`

The `src` directory contains your application code. Feel free to add another folders there. All files inside this folder should be in `App` namespace since they are autoloaded.


#### `/storage`

There are placed cache and temporary files.


#### `/tests`

This directory contain test files. Melonly uses [Pest](https://pestphp.com) framework for handling unit tests.


#### `/vendor`

Composer packages are installed there.


### `src/` Directory

#### `/Controllers`

Default HTTP controllers directory.


#### `/Exceptions`

Application exceptions are stored there. To create new exception run `php melon new:exception SomeException` command.


#### `/Middleware`

All generated and built-in middleware is stored in this directory.


#### `/Models`

Default database models directory.


#### `/Services`

Additional directory containing service pattern classes. It is not directly related to Melonly but it's rather a convention. To create this directory run `php melon new:service` command.


#### `/Commands`

This directory doesn't exist by default. It contains your custom console commands generated by `new:command` command.


#### `/Emails`

This directory doesn't exist by default as well. It contains application e-mail classes.


## Command Line Interface

### Useful Built-In Commands

Melonly ships with Melon CLI - terminal mode client for developers. It includes many useful commands during development. Using them you can quickly generate controllers, models or even custom commands. You can test some of them:

```shell
# Display information about framework version

> php melon -v
> php melon --version
```

```shell
# Create new controller

> php melon new:controller PostController
```

```shell
# Create new database model

> php melon new:model Post
```

```shell
# Get list of all built-in commands

> php melon command:list
```


### Custom Commands

You can create your own commands using this command:

```shell
> php melon new:command SayHello
```

Then you'll be able to write custom command in generated file in `/src/Commands` directory.


## Routing

### Basic Routing with Callbacks

To register application routes, edit the `routing/routes.php` file.

```php
use Melonly\Routing\Facades\Route;

Route::get('/my-route', function (Request $request, Response $response): void {
    $response->send('My first route');
});
```

As you can see, you can supply a simple callback with injected request & response objects to return some response.
Enter the `localhost:5000/my-route` route and look for the result.

You can also register multiple routes to the same action using array:

```php
Route::get(['/home', '/login', 'register'], function () ...);
```


### Routing Parameters

You can create dynamic routes by adding parameters. To make parameters in URL, use square brackets and provide param name.

```php
use Melonly\Routing\Facades\Route;

Route::get('/users/{id}', function (Request $request, Response $response): void {
    $response->send('User id: ', $request->parameter('id'));
});
```

Retrieving parameters is done using `parameter()` method from `Request` object.

After entering to `/users/356` path you will see "User id: 356".


### Controllers

Rather than passing closures to route definitions there is more common to use `controller` classes. Melonly utilizes MVC structure so controllers are supported out-of-the-box. To create new controller run following command:

```shell
php melon new:controller SomeController
```

It will create a new file: `src/Controllers/SomeController.php` with the following structure:

```php
namespace App\Controllers;

use Melonly\Http\Controller;
use Melonly\Http\Request;
use Melonly\Http\Response;

class SomeController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        // 
    }
}
```

To assign controller method to route use the array syntax:

```php
use App\Controllers;

Route::get('/users', [Controllers\ControllerName::class, 'index']);
```

Now on specified `/users` route Melonly will invoke `index` method from `ControllerName`. If method name has not been provided, then `index` will be implicit.


#### Single-Action Controllers

If the controller has only one method you can pass controller class name instead of array to route definition:

```php
Route::get('/users', Controllers\ControllerName::class);
```

`ControllerName` should have a `handle` method invoked when using single-action controller:

```php
class ControllerName extends Controller
{
    public function handle(): void
    {
        // 
    }
}
```


### Middleware

Middleware can be used for filtering incoming requests or performing some actions on route enter. You can assign middleware to routes passing array argument to route definition.

Melonly provides built-in middleware `auth` which checks if user is logged in. If not, the user will be redirected to `/login` route.

```php
Route::get('/profile', [Controllers\UserController::class, 'show'], ['middleware' => 'auth']);
```

To create custom middleware you can run Melon command:

```shell
> php melon new:middleware MiddlewareName
```

Then register middleware alias in `config/http.php`:

```php
'middleware' => [
    'alias' => \App\Middleware\MiddlewareName::class,
],
```

Middleware is stored in `src/Middleware` directory. Edit created file and you'll be able to use new middleware:

```php
Route::get('/users', [Controllers\ControllerName::class, 'index'], ['middleware' => 'alias']);
```


## Views

Modern applications deal with user interfaces. Therefore Melonly handles special HTML templates which help You building applications in pleasant way.
View files / templates are located in `frontend/views` directory. You can display a view returning it from the response, using the "dot" syntax.


### Displaying a View

Rendering views can be done with `view` method on `Response` object:

```php
$response->view('pages.login');
```


### Passing Variables

To display variable in a view, you can pass an array with supplied variable names and values.

```php
$response->view('pages.dashboard', [
    'username' => Auth::user()->name,
    'array' => [1, 2, 3],
]);
```

Then you may use it in the template:

```html
<h1>Welcome, {{ $username }}</h1>
```

Note that you cannot pass objects to components when using the default view engine.

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


### Twig Templates

However if you don't like these templates, Melonly supports [Twig](https://twig.symfony.com) engine for handling views. After setting the `engine` option in `config/view.php` file to `Twig`, you can use Twig templates in your app (note that view file extensions would be `.html.twig` then).

```html
Example Twig template

{% for item in array %}
    <li>{{ item }}</li>
{% endfor %}
```


## Database

### Query Builder

Handling databases in web applications is very important. Melonly provides a simple query builder for retrieving data from database and creating new records.

To fetch some data using the query builder interface just use imported `DB` facade:

```php
use Melonly\Database\Facades\DB;

$user = DB::from('users')->where('id', '=', 1)->fetch();

echo $user->name;
```

With this query builder you can also fetch only specified columns by passing field array to `fetch()` method or using `select([...])`.

```php
// Fetch user with only name and email
$user = DB::from('users')->fetch(['name', 'email']);

// Equivalent to:
$user = DB::from('users')->select(['name', 'email'])->fetch();
```

Available query builder methods: `select`, `where`, `orWhere`, `limit` and `orderBy`.

### Raw SQL Queries

Alternatively you can execute a raw SQL query with `query()` method though we do not recommend that:

```php
$name = DB::query('select `name` from `users` where `id` = 1');

DB::query('insert into `users` ...');
```


### Models

Due the fact Melonly is a MVC framework, it follows the Model pattern. Each table in your database can have corresponding 'Model'.

To create a model, use CLI command:

```shell
> php melon new:model ModelName
```

For example:

```shell
> php melon new:model Post
```

It will create `src/Models/Post.php` model file with the following structure:

```php
namespace App\Models;

use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\PrimaryKey;
use Melonly\Database\Model;

class Post extends Model
{
    #[PrimaryKey]
    public $id;

    #[Column(type: 'string')]
    public $name;

    #[Column(type: 'datetime', nullable: true)]
    public $created_at;
}
```

Column data typing is done using the `Column` attribute.

Then if your database contains `posts` table, you can retrieve data from that table with model class. Melonly uses plural '-s' suffix by default to retrieve tables but it can be overwritten by setting `protected $table` property in a model.


#### Retrieving Data

By chaining methods it is possible to create complex `where` clauses. Some of available methods are: `where`, `orWhere`, `orderBy`. Data returned by the `fetch()` method is type of `Vector` (in case of single result it is an instance of `Record` or your model).

```php
use App\Models\Post;

$post = Post::where('id', '=', 1)->orWhere('title', 'like', '%PHP%')->fetch();

$postsOrdered = Post::orderBy('created_at', 'desc')->limit(10)->fetch();

$title = $post->title;
```


#### Creating Records

You can also create new records using models. Use the `create` method with passed array with column values to create data in your table:

```php
use App\Models\User;
use Melonly\Encryption\Facades\Hash;

User::create([
    'name' => $username,
    'email' => $email,
    'password' => Hash::hash($password),
]);
```

Alternative way to create a record is to make a model instance with specified fields and `save()` method:

```php
use App\Models\User;

$user = new User();

$user->name = $username;
$user->email = $email;
$user->password = Hash::hash($password);

$user->save();
```

As you can see, dealing with database is super easy with Melonly.


### Migrations

Melonly comes with basic built-in table migration system. It currently supports only creating tables but it will be improved in future releases.

To create new migration run `melon` command:

```shell
> php melon new:migration create_posts_table
```

Migrations are stored in `database/migrations` directory. To run migrations you have to specify database credentials in `.env` file. Then run:

```shell
> php melon migrate
```

Open your database and look for changes.


## Authentication

Authentication (user login system) is very needed on modern web applications. Melonly ships with a simple auth system.

All you need is to have users table in your database with e-mails and hashed passwords (see: [Hashing](#encryption--hashing)). You can create user record using DB model:

```php
use App\Models\User;
use Melonly\Encryption\Facades\Hash;

// Example user registration
User::create([
    'name' => $username,
    'email' => $email,
    'password' => Hash::hash($password),
]);
```

Now let's create user HTML login form in your view:

```html
<form action="/login" method="post">
    [csrf]

    <input type="email" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">

    <div>{{ $message }}</div>

    <button>Log in</button>
</form>
```

```php
Route::get('/login', function (Request $request, Response $response): void {
    $response->view('pages.login', [
        // Get message if authentication failed
        'message' => $request->redirectData('message'),
    ]);
});
```

Note that you have to include `[csrf]` field in the form to secure users from [Cross-site request forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery) attacks. If you're using Twig template engine instead of the built-in one, just add this hidden input:

```html
<input type="hidden" name="csrf_token" value="{{ csrfToken() }}">
```

Now we can set up the POST `/login` route with authentication logic:

```php
use Melonly\Authentication\Facades\Auth;

Route::post('/login', function (Request $request, Response $response): void {
    $email = $request->get('email');
    $password = $request->get('password');

    // Redirect to home route if case of success
    if (Auth::login($email, $password)) {
        $response->redirect('/');

        return;
    }

    // Redirect back to login form on failure
    // Pass error message
    $response->redirect('/login', ['message' => 'Invalid e-mail or password.']);
});
```

Now you can test the login system. When provided e-mail and password matches the database data, user will be authenticated.

Retrieving authenticated user data is done using `Auth::user()` method which returns `App\Models\User` model.

```php
$username = Auth::user()->name;
```

To log the user out, use the `logout()` method.

```php
Route::get('/logout', function (): void {
    // Log out user and redirect to /login route
    Auth::logout();
});
```


## Session

HTTP session is a useful mechanism. You can store there some user data or other things. To manipulate HTTP session, use the `Session` class.

```php
use Melonly\Http\Session;

// Set session variable
Session::set('invalid_data', 'Password is invalid.');

// Retrieve data
$message = Session::get('invalid_data');

// Check whether data is set or not
$isset = Session::isSet('invalid_data');

// Delete session data
Session::unset('invalid_data');
```


## Validation

Request data validation with Melonly is super easy. Look how it works:

```php
use Melonly\Validation\Facades\Validate;

// In some route definition or controller
Validate::check([
    'username' => ['required', 'min:3', 'max:30', 'alphanumeric'],
    'email' => ['email', 'max:30'],
    'age' => ['int'],
]);
```

Melonly will check provided data against specified rules. If validation fails the 422 status is returned.

Available validation rules are listed here:

- accepted
- alphanumeric
- bool
- domain
- email
- file
- float
- image
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


## Files

Melonly provides a simple file manipulation system with many useful functions:

```php
use Melonly\Filesystem\File;

$path = 'path/to/file.jpg';

// Check if file exists
File::exists($path);

// Create / delete a file
File::create($path, 'Some content');
File::delete($path);

// Copy file
File::copy($path, 'new/file/path.jpg');
```


### Image files

Melonly utilizes [Intervention/Image](https://image.intervention.io/v2) library for image manipulation. You can use `Image` helper to manage `.jpg`, `.png` and `.gif` image files.

```php
use Melonly\Filesystem\Image;

$image = Image::make('image1.jpg');

$image->resize(560, 320);
$image->save('image.jpg');
```


## Helpers

Melonly includes many useful global functions / helpers used by Melonly internally. But you can use them in your code if you find it convinient.

### String Manipulation

```php
use Melonly\Support\Helpers\Str;

$string = 'lorem ipsum';

// Uppercase string
$string = Str::uppercase($string);

// Replace occurences
$string = Str::replace(' ', '_', $string); // Output: 'lorem_ipsum'

// PascalCase string
$string = Str::pascalCase($string); // Output: 'LoremIpsum'
```


### Date and Time Manipulation

Melonly uses [Carbon](https://carbon.nesbot.com/docs/) to manipulate date and time under the hood.

```php
use Melonly\Support\Helpers\Time;

$date = Time::now()->isoFormat('Y_MM_D'); // Output: 2022_02_02
```


### UUID Generation

```php
use Melonly\Support\Helpers\Uuid;

// Generate unique ID (UUID v4)
$id = Uuid::v4();
```


### JSON

```php
use Melonly\Support\Helpers\Json;

$data = ['id' => 145];

$json = Json::encode($data); // Return JSON object
```


### Other Helpers

```php
dd('Some data'); // Dump information about variable or some data and finish script

throwIf($condition, new Exception); // Throw new exception if condition is true

redirect('/login'); // Redirect user to given URL

$vector = vector(1, 2, 3); // Create a new vector with provided values

$message = trans('app.welcome'); // Get language translation entry

$option = config('file.option_name'); // Retrieve option from config file
```


## Encryption and Hashing

On backend you'll often need to encrypt or hash some data, e.g. user password in database. Look how it works:

```php
use Melonly\Encryption\Facades\Hash;

$passwordHash = Hash::hash($request->get('password'));

// Compare provided password with database hash
$isPasswordCorrect = Hash::equals($request->get('password'), $user->password);
```


## Sending Emails

Melonly has an built in interface for sending e-mails:

```php
use Melonly\Mailing\Facades\Mail;

Mail::send('recipient@address', 'Subject', 'My message');
```

Note that you have to setup PHP config on your server to send emails.

Address from which messages are sent is specified in `MAIL_ADDRESS` in `.env` file.


## Frontend Frameworks (React and Vue)

Melonly has built-in scaffolding command for installing frontend frameworks like [React](https://reactjs.org) and [Vue](https://vuejs.org). Before you start, run the following command to install `webpack` included in `package.json` by default:

```shell
> npm install
```

Then you'll be able to create starter framework template:

```shell
> php melon scaffold:react

> php melon scaffold:vue
```

These commands will create corresponding `react` or `vue` directories in `frontend` location and install Node dependencies.

After running `npm start` command `webpack` will compile your code in watch mode. `npm run build` will build output only once. You can set development / production mode in `webpack.config.js`. All configuration is stored in this file.


## WebSockets and Broadcasting

Modern web applications often need WebSocket connection. Melonly supports two popular broadcasting drivers out-of-the-box: [Pusher](https://pusher.com) and [Ably](https://ably.com). You can configure the driver in `.env` file:

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

Install the choosen driver package:

```shell
> composer require pusher/pusher-php-server

# or:

> composer require ably/ably-php
```

After configuration you may create your first broadcasts. Look how it works:

```php
use Melonly\Broadcasting\Facades\WebSocket;

WebSocket::broadcast('channel-name', 'EventName', $data);
```

Then on the client side you can listen for broadcasted events using your driver.


## Testing

Melonly utilizes [Pest](https://pestphp.com) framework for handling unit tests. Test files live inside `/tests` directory. To create new test file, run `new:test` command:

```shell
> php melon new:test:unit SomeTest

> php melon new:test:feature SomeTest
```

This will create new test with the following structure:

```php
test('asserts true is true', function () {
    $this->assertTrue(true);

    expect(true)->toBeTrue();
});
```

To run tests you can use this command:

```shell
> ./vendor/bin/pest
```


## Deployment

When you're moving to server from the local environment, you will need to change serveral settings. Firstly let's change the `APP_DEVELOPMENT` entry to `false` in `.env` file. It will prevent from leaking code snippets visible on dev exception page.

Then if your server supports setting root web directory, set it to `public` directory. If it's not available, you have to upload all files (except these inside `public`) to a directory **above** your public server root. Then set the `APP_PUBLIC` option to `public_html` / `private_html` or whatever you have and upload there files from project `public` directory. Finally adjust the `INCLUDE_PATH` constant in `/public/index.php` to your structure.


## Documentation

*COMING SOON*: If you want to dig deeper and learn some advanced Melonly features, you may visit the official [documentation](website).


## Contributing

Melonly is an open-source framework. Thank you if you considered contributing. We encourage you to opening [issues](https://docs.github.com/en/issues/tracking-your-work-with-issues/about-issues) and  [pull requests](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests) on the repository.

If you discovered a bug or security vulnerability please open issue / pull request or email me: dom.rajkowski@gmail.com.


## License

Melonly is open-source framework licensed under the [MIT license](melonly/LICENSE).

Author: [Doc077](https://github.com/Doc077)

Contact: dom.rajkowski@gmail.com

<a href="https://melonly.dev"><img align="center" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg" width="30"></a>
&nbsp;&nbsp;&nbsp;
<a href="https://melonly.dev"><img align="center" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-plain-wordmark.svg" width="48"></a>
&nbsp;&nbsp;&nbsp;
<a href="https://melonly.dev"><img align="center" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-plain.svg" width="44"></a>
&nbsp;&nbsp;&nbsp;
<a href="https://melonly.dev"><img align="center" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-plain-wordmark.svg" width="66"></a>
&nbsp;&nbsp;&nbsp;
<a href="https://melonly.dev"><img align="center" src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/webpack/webpack-plain-wordmark.svg" width="88"></a>

[website]: https://melonly.dev
