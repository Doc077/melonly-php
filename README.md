<img src="public/logo.png" width="68">

## Melonly PHP Framework

Melonly is a fast, modern web application development framework for PHP. It makes easy to create secure and fast web applications with nice developer experience.

*COMING SOON*: Melonly documentation will be available on the [official site](https://melonly.dev).

- [Melonly PHP Framework](#melonly-php-framework)
- [Requirements](#requirements)
- [Installation](#installation)
- [Running Application & Development](#running-application--development)
- [Directory Structure](#directory-structure)
  - [Root directory](#root-directory)
    - [config](#config)
    - [database](#database)
    - [frontend](#frontend)
    - [melonly](#melonly)
    - [plugins](#plugins)
    - [public](#public)
    - [src](#src)
    - [storage](#storage)
    - [tests](#tests)
  - [```src/``` Directory](#src-directory)
    - [Controllers](#controllers)
    - [Exceptions](#exceptions)
    - [Models](#models)
    - [Services](#services)
    - [Commands](#commands)
    - [Emails](#emails)
- [Console Interface](#console-interface)
  - [Useful Melonly Console Commands](#useful-melonly-console-commands)
- [Routing](#routing)
  - [Basic Closure Routing](#basic-closure-routing)
  - [Routing Parameters](#routing-parameters)
  - [Controllers](#controllers-1)
- [Views](#views)
  - [Displaying a View](#displaying-a-view)
  - [Passing Variables](#passing-variables)
  - [Templates](#templates)
  - [Twig Templates](#twig-templates)
- [Database](#database-1)
  - [Raw SQL Queries](#raw-sql-queries)
  - [Models](#models-1)
    - [Retrieving Data](#retrieving-data)
    - [Creating Records](#creating-records)
  - [Migrations](#migrations)
- [Validation](#validation)
- [Making HTTP Requests](#making-http-requests)
- [Files](#files)
  - [Image files](#image-files)
- [Helpers](#helpers)
  - [String Helpers](#string-helpers)
  - [Time Manipulation Helpers](#time-manipulation-helpers)
  - [UUID Generation](#uuid-generation)
  - [JSON Helpers](#json-helpers)
  - [Other Helpers](#other-helpers)
- [Encryption & Hashing](#encryption--hashing)
- [Sending Emails](#sending-emails)
- [WebSockets & Broadcasting](#websockets--broadcasting)
- [Deployment](#deployment)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)

## Requirements

- PHP 8.1+
- [Composer](https://getcomposer.org) installed
- PDO PHP Extension
- cURL PHP Extension
- PHP Image Processing Extension


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


## Directory Structure

Default Melonly application structure consists of several main folders.


### Root directory

#### config

This directory contains configuration files for your application. You can create custom file inside this directory and refer to it using ```config(string $file, string $key)``` helper.


#### database

In this directory are created database migrations. You can also store ```sqlite``` database here.


#### frontend

There are placed all files related to frontend side like views or uncompiled styles/scripts.


#### melonly

This folder contains Melonly framework files. You don't need to edit anything there.


#### plugins

In ```plugins``` directory are stored installed ```Composer``` packages.


#### public

This is the only directory visible to users. It prevents from direct access to source code. ```public``` folder contains ```.htaccess``` and ```index.php``` files. This is where you should put client side things like compiled styles, JS scripts and images.


#### src

The ```src``` directory contains your application code. Feel free to add another folders there. All files inside this folder should be in ```App``` namespace since they are autoloaded.


#### storage

There are placed cache and temporary files.


#### tests

This testing directory does not exist by default. It can be created by running command:

```shell
> php melon test:template
```

After this command the ```tests``` directory will be created along with ```phpunit.xml``` file.


### ```src/``` Directory

#### Controllers

Default HTTP controllers directory.


#### Exceptions

Application exceptions folder.


#### Models

Default database models directory.


#### Services

Additional directory containing service pattern classes. It is not directly related to Melonly but it's rather a convention. If you don't use services you can delete this directory.


#### Commands

This directory doesn't exist by default. It contains your custom console commands generated by ```new:command``` command.


#### Emails

This directory doesn't exist by default as well. It contains application e-mail classes.


## Console Interface

### Useful Melonly Console Commands

Melonly ships with Melon CLI - terminal mode client for developers. It includes many useful commands during development. Using them you can quickly generate controllers, models or even custom commands. You can test some of them:

```shell
# Display information about framework version

> php melon -v
```

```shell
# Create new controller file

> php melon new:controller PostController
```

```shell
# Create new database model

> php melon new:model Post
```

```shell
# Create custom CLI command

> php melon new:command SayHelloCommand
```

```shell
# Get list of all built-in commands

> php melon info
```


## Routing

### Basic Closure Routing

To register application routes, edit ```routing/routes.php``` file.

```php
use Melonly\Routing\Facades\Route;

Route::get('/my-route', function (Request $request, Response $response): void {
    $response->send('My first route');
});
```

As you can see, you can supply a simple callback with injected request & response objects to return some response.
Enter the ```localhost:5000/my-route``` route and look for the result.

You can also register multiple routes using array:

```php
Route::get(['/home', '/login', 'register'], ...);
```


### Routing Parameters

Melonly supports dynamic routes with parameters. Mark the path segment with ```{parameterName}``` to make route dynamic. Note that parameter names must be unique.

Retrieving parameters is done using ```parameter()``` method from ```Request``` object.

```php
use Melonly\Routing\Facades\Route;

Route::get('/users/{userId}', function (Request $request, Response $response): void {
    $response->send('User id: ', $request->parameter('userId'));
});
```

After entering to ```/users/356``` path you will see "User id: 356".


### Controllers

Rather than passing closures to route definitions there is more common to use ```controller``` classes. Melonly utilizes MVC structure so controllers are supported out of the box. To create basic controller run command:

```shell
php melon new:controller ControllerName
```

It will create a new file: ```src/Controllers/ControllerName.php``` with the following structure:

```php
namespace App\Controllers;

use Melonly\Http\Request;
use Melonly\Http\Response;

class ControllerName {
    public function index(Request $request, Response $response): void {
        // 
    }
}
```

To assign controller method to route use the array syntax:

```php
use App\Controllers\ControllerName;

Route::get('/users', [ControllerName::class, 'index']);
```

Now on specified ```/users``` route Melonly will invoke ```index``` method from ```ControllerName```.


## Views

Modern applications deal with user interfaces. Therefore Melonly handles special HTML templates which help You building applications in pleasant way.
View files / templates are located in ```frontend/views``` directory. You can display a view returning it from the response, using the "dot" syntax.


### Displaying a View

```php
Route::get('/login', function (Request $request, Response $response): void {
    $response->view('pages.login');
});
```


### Passing Variables

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


### Twig Templates

However if you don't like these templates, Melonly supports [Twig](https://twig.symfony.com) engine for handling views. After setting the ```engine``` option in ```config/view.php``` file to ```Twig```, you can use Twig templates in your app (note that view file extensions would be ```.html.twig``` then).

```html
Example Twig template

{% for item in array %}
    <li>{{ item }}</li>
{% endfor %}
```


## Database

### Raw SQL Queries

Handling databases in web applications is very important. Melonly provides a simple interface for querying data from database. To execute a raw SQL query, use ```DB``` facade:

```php
use Melonly\Database\Facades\DB;

$name = DB::query('select `name` from `users` where `id` = 1');
```


### Models

#### Retrieving Data

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

$postsOrdered = Post::orderBy('created_at', 'desc')->limit(10)->fetch();

$title = $post->title;
```

As you can see, dealing with database is super easy with Melonly.


#### Creating Records

You can also create new records using models. Use the ```create``` method with passed array with column values to create data in your table:

```php
use App\Models\User;
use Melonly\Encryption\Facades\Hash;

User::create([
    'name' => $username,
    'email' => $email,
    'password' => Hash::hash($password),
]);
```


### Migrations

Melonly comes with basic built-in table migration system. It currently supports only creating tables but we'll improve it in future releases.

To create new migration run ```melon``` command:

```shell
> php melon new:migration create_posts_table
```

Migrations are stored in ```database/migrations``` directory. To run migrations you have to specify database credentials in ```.env``` file. Then run:

```shell
> php melon migrate
```

Open your database and look for changes.


## Validation

Request data validation with Melonly is super easy. Look how it works:

```php
use Melonly\Validation\Facades\Validate;

// In some route definition
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

Melonly utilizes [Intervention Image](https://image.intervention.io/v2) library for image manipulation. You can use ```ImageFile``` helper to manage ```jpg```, ```png``` and ```gif``` image files.

```php
use Melonly\Filesystem\Image;

$image = Image::make('image1.jpg');

$image->resize(560, 320);
$image->save('image.jpg');
```


## Helpers

Melonly offers many useful global functions (helpers) used by Melonly itself. Feel free to use them in your code if you find it convinient.

### String Helpers

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


### Time Manipulation Helpers

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


## Sending Emails

Melonly has an built in interface for sending e-mails:

```php
use Melonly\Mailing\Facades\Mail;

Mail::send('recipient@address', 'Subject', 'My message');
```

Note that you have to setup PHP config on your server to send emails.

Address from which messages are sent is specified in ```MAIL_ADDRESS``` in ```.env``` file.


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

Install the choosen driver package:

```shell
> composer require pusher/pusher-php-server

# or

> composer require ably/ably-php
```

After configuration you may create your first broadcasts. Look how it works:

```php
use Melonly\Broadcasting\Facades\WebSocket;

WebSocket::broadcast('channel-name', 'EventName', $someData);
```

Then on the client side you can listen for broadcasted events using your driver.


## Deployment

When moving to server from local environment you'll need to adjust some settings. Firstly, change the ```APP_DEVELOPMENT``` entry to ```false``` in ```.env``` file. It will prevent from leaking some code visible on exception page.

Then if your server supports "pointing" root path to choosen directory, set the pointer to ```public``` folder. If not, upload all files except these inside ```public``` to a directory **above** your server root. Then set the ```APP_PUBLIC``` option to ```public_html``` / ```private_html``` or whatever you have and upload there files from project ```public``` directory.


## Documentation

*COMING SOON*: If you want to dig deeper and learn some advanced Melonly features, you may visit the official [documentation](https://melonly.dev/docs).


## Contributing

Melonly is an Open Source framework. If You want to make Melonly even better, we appreciate it. You can clone the repository and commit changes on created branch. Then open a new [pull request](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests) and we'll merge them if it would be something valuable and error-free without any security vulnerabilities.

We also encourage you to opening Issues and discussions on the repository.


## License

Melonly is licensed under the [MIT license](LICENSE).

Author: Doc077 (dom.rajkowski@gmail.com)
