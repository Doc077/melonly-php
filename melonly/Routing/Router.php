<?php

namespace Melonly\Routing;

use Melonly\Container\Container;
use Melonly\Filesystem\File;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Http\Method as HttpMethod;
use Melonly\Http\Mime;
use Melonly\Support\Helpers\Json;
use Melonly\Support\Helpers\Regex;
use Melonly\Support\Helpers\Str;
use Melonly\Views\View;
use ReflectionClass;

class Router implements RouterInterface
{
    protected array $patterns = [];

    protected array $methods = [];

    protected array $actions = [];

    protected array $middleware = [];

    protected array $redirects = [];

    public function add(HttpMethod|string $method, string|array $uri, callable|array $action, array $data = []): void
    {
        /**
         * Register multiple routes in case of array argument.
         */
        if (is_array($uri)) {
            foreach ($uri as $address) {
                $this->add($method, $address, $action, $data);
            }

            return;
        }

        $uri = $this->cleanPath($uri);

        /**
         * Convert HTTP method enum to string.
         */
        is_string($method)
            ? $method = Str::uppercase($method)
            : $method = $method->value;

        /**
         * Create pattern for dynamic parameters and route URI.
         */
        $pattern = Regex::replace('/\{(.*?)\}/', '(?P<$1>(.*))', $uri) . '(\\?.*?)?';

        $pattern = '/^' . $method . Str::replace('/', '\/', $pattern) . '$/';

        /**
         * Register route data.
         */
        $this->patterns[$pattern] = $pattern;
        $this->actions[$pattern] = $action;

        /**
         * Assign middleware to route.
         */
        foreach ($data as $key => $value) {
            if ($key === 'middleware') {
                $this->middleware[$pattern] = $value;
            }
        }
    }

    public function get(string|array $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Get, $uri, $action, $data);
    }

    public function post(string|array $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Post, $uri, $action, $data);
    }

    public function put(string|array $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Put, $uri, $action, $data);
    }

    public function patch(string|array $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Patch, $uri, $action, $data);
    }

    public function delete(string|array $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Delete, $uri, $action, $data);
    }

    public function options(string|array $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Options, $uri, $action, $data);
    }

    public function any(string $uri, callable|array $action, array $data = []): void
    {
        $this->add(HttpMethod::Get, $uri, $action, $data);

        $this->add(HttpMethod::Post, $uri, $action, $data);

        $this->add(HttpMethod::Put, $uri, $action, $data);

        $this->add(HttpMethod::Patch, $uri, $action, $data);

        $this->add(HttpMethod::Delete, $uri, $action, $data);

        $this->add(HttpMethod::Options, $uri, $action, $data);
    }

    public function view(string $uri, string $view, array $variables = [], array $data = []): void
    {
        $this->add(HttpMethod::Get, $uri, function (Response $response) use ($view, $variables) {
            $response->view($view, $variables);
        }, $data);
    }

    public function evaluate(): void
    {
        $uri = Container::get(Request::class)->uri();

        $uri = $this->cleanPath($uri);

        /**
         * If URI requests a file, send it and return
         */
        if (array_key_exists('extension', pathinfo($uri)) && pathinfo($uri)['extension']) {
            $this->handleFileRequest($uri);

            return;
        }

        /**
         * Check if URI matches with one of registered routes.
         */
        $this->checkMatchedRoute($uri);
    }

    protected function cleanPath(string $uri): string
    {
        /**
         * Trim leading slash.
         */
        if ($uri[0] === '/') {
            $uri = Str::substring($uri, 1);
        }

        return $uri;
    }

    protected function checkMatchedRoute(string $uri): void
    {
        $matchesOneRoute = false;

        foreach ($this->patterns as $pattern) {
            $matchPattern = $_SERVER['REQUEST_METHOD'] . $uri;

            if (preg_match($pattern, $matchPattern, $parameters)) {
                $matchesOneRoute = true;

                if (array_key_exists($pattern, $this->redirects)) {
                    header('Location: ' . $this->redirects[$pattern]);
                }

                $parameterList = [];

                /**
                 * Get only non-numeric matches & remove query strings.
                 */
                foreach ($parameters as $key => $value) {
                    if (!is_numeric($key)) {
                        $parameterList[$key] = explode('?', $value)[0];
                    }
                }

                Container::get(Request::class)->setParameters($parameterList);

                $action = $this->actions[$pattern];

                /**
                 * Call controller method in case of array.
                 */
                if (is_array($action)) {
                    $this->handleController($action[0], $action[1] ?? 'index');
                }

                /**
                 * Call default controller method in case of class string.
                 */
                if (is_string($action)) {
                    $this->handleController($action, 'handle');
                }

                /**
                 * Call route callback in case of callable.
                 */
                if (is_callable($action)) {
                    $this->handleClosure($pattern);
                }

                $this->returnResponse($pattern);

                break;
            }
        }

        if (!$matchesOneRoute) {
            Container::get(Response::class)->abort(404);
        }
    }

    protected function handleClosure(string $pattern): void
    {
        $services = Container::resolveDependencies($this->actions[$pattern]);

        $this->actions[$pattern](...$services);
    }

    protected function handleController(string $class, string $method): void
    {
        $classReflection = new ReflectionClass($class);

        $constructor = $classReflection->getConstructor();

        $constructorServices = $constructor ? Container::resolveDependencies($constructor) : [];

        $controller = new $class(...$constructorServices);

        $closure = $classReflection->getMethod($method)->getClosure($controller);

        $methodServices = Container::resolveDependencies($closure);

        $controller->{$method}(...$methodServices);
    }

    protected function handleFileRequest(string $uri): void
    {
        $extension = pathinfo($uri)['extension'];

        $mime = 'text/plain';

        /**
         * Abort with 404 status if file not found.
         */
        if (!File::exists(__DIR__ . '/../../' . config('app.public') . '/' . $uri)) {
            Container::get(Response::class)->abort(404);

            return;
        }

        $extensionMimeTypes = Mime::TYPES;

        if ($extension === 'php' || $uri === '.htaccess') {
            Container::get(Response::class)->abort(404);

            exit();
        }

        if (array_key_exists(pathinfo($uri)['extension'], $extensionMimeTypes)) {
            $mime = $extensionMimeTypes[$extension];
        }

        if (pathinfo($uri)['extension'] === 'css') {
            $mime = 'text/css';
        }

        header('Content-Type: ' . $mime);

        print(readfile(__DIR__ . '/../../' . config('app.public') . '/' . $uri));
    }

    protected function handleMiddleware(string $pattern): void
    {
        $class = config('http.middleware')[$this->middleware[$pattern]];

        $classReflection = new ReflectionClass($class);

        $closure = $classReflection->getMethod('handle')->getClosure(new $class());

        $services = Container::resolveDependencies($closure);

        (new $class())->handle(...$services);
    }

    protected function returnResponse(string $pattern): void
    {
        /**
         * Handle route middleware if defined.
         */
        if (array_key_exists($pattern, $this->middleware)) {
            $this->handleMiddleware($pattern);
        }

        /**
         * Render view or show raw response data.
         */
        $view = Container::get(Response::class)->getView()[0];
        $viewVariables = Container::get(Response::class)->getView()[1];

        if ($view) {
            $view = Str::replace('.', '/', $view);

            View::renderView($view, $viewVariables);

            return;
        }

        /**
         * Set response HTTP status code.
         */
        http_response_code(Container::get(Response::class)->getStatus());

        /**
         * Return response content.
         * In case of array return JSON.
         */
        $responseData = Container::get(Response::class)->getData();

        if (is_array(Container::get(Response::class)->getData())) {
            header('Content-Type: application/json');

            print(Json::encode($responseData));
        } else {
            print($responseData);
        }
    }
}
