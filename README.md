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

Then remove .git folder from the main directory and start your app.


## Run Application & Development

To run your application on local environment, use the command line:

```
# Run the Melonly development server

> php melon server
```

Your application will be available on [localhost:5000](http://localhost:5000).


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

To register application routes, edit routes/web.php file.

```php
Route::get('/my-path', function (Request $request, Response $response): void {
    $response->send('My first route');
});
```

As you can see, you can supply a simple callback with injected request & response objects to return some response.
Enter the localhost:5000/my-path route and look for the result.


## License

Melonly is licensed under the [MIT license](LICENSE).
