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


## Useful Melonly CLI Commands

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

Melonly templates allow to write simple PHP expressions directly in HTML.

```html
<div>Price: {{ 2 + 2 * 6 }} USD</div>
```

---


## License

Melonly is licensed under the [MIT license](LICENSE).

Author: Doc077
