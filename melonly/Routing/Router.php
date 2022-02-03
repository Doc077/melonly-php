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

class Router implements RouterInterface {
    protected array $patterns = [];

    protected array $methods = [];

    protected array $actions = [];

    protected array $middleware = [];

    protected array $redirects = [];

    public function add(string|HttpMethod $method, string|array $uri, callable|array $action, array $data = []): void {
        /**
         * Register multiple routes in case of array argument.
         */
        if (is_array($uri)) {
            foreach ($uri as $address) {
                $this->add($method, $address, $action, $data);
            }

            return;
        }

        /**
         * Trim leading slash.
         */
        if ($uri[0] === '/') {
            $uri = Str::substring($uri, 1);
        }

        /**
         * Convert HTTP method enum to string.
         */
        if (!is_string($method)) {
            $method = $method->value;
        } else {
            $method = Str::uppercase($method);
        }

        /**
         * Create pattern for dynamic parameters and route URI.
         */
        $pattern = Regex::replace('/\{(.*?)\}/', '(?P<$1>(.*))', $uri) . '(\\?.*?)?';

        $pattern = '/^' . $method . Str::replace('/', '\/', $pattern) . '$/';

        /**
         * Add route data to static arrays.
         */
        $this->patterns[$pattern] = $pattern;
        $this->actions[$pattern] = $action;
    }

    public function get(string|array $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Get, $uri, $action, $data);
    }

    public function post(string|array $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Post, $uri, $action, $data);
    }

    public function put(string|array $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Put, $uri, $action, $data);
    }

    public function patch(string|array $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Patch, $uri, $action, $data);
    }

    public function delete(string|array $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Delete, $uri, $action, $data);
    }

    public function options(string|array $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Options, $uri, $action, $data);
    }

    public function any(string $uri, callable|array $action, array $data = []): void {
        $this->add(HttpMethod::Get, $uri, $action, $data);
        $this->add(HttpMethod::Post, $uri, $action, $data);
        $this->add(HttpMethod::Put, $uri, $action, $data);
        $this->add(HttpMethod::Patch, $uri, $action, $data);
        $this->add(HttpMethod::Delete, $uri, $action, $data);
        $this->add(HttpMethod::Options, $uri, $action, $data);
    }

    public function evaluate(): void {
        $uri = Container::get(Request::class)->uri();

        /**
         * Trim leading slash.
         */
        if ($uri[0] === '/') {
            $uri = Str::substring($uri, 1);
        }

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

    protected function checkMatchedRoute(string $uri): void {
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

                //var_dump($parameterList);exit;

                Container::get(Request::class)->setParameters($parameterList);

                $action = $this->actions[$pattern];

                /**
                 * Call controller method in case of array argument.
                 */
                if (is_array($action)) {
                    $this->handleController($action[0], $action[1]);
                }

                /**
                 * Call route callback in case of callable argument.
                 */
                if (is_callable($action)) {
                    $this->handleClosure($pattern);
                }

                $this->returnResponse();

                break;
            }
        }

        /**
         * If route has not been found, throw 404 error.
         */
        if (!$matchesOneRoute) {
            Container::get(Response::class)->abort(404);
        }
    }

    protected function handleClosure(string $pattern): void {
        $services = Container::resolve($this->actions[$pattern]);

        $this->actions[$pattern](...$services);
    }

    protected function handleController(string $class, string $method): void {
        $classReflection = new ReflectionClass($class);
        $controller = new $class();

        $closure = $classReflection->getMethod($method)->getClosure($controller);

        $services = Container::resolve($closure);

        $controller->{$method}(...$services);
    }

    protected function handleFileRequest(string $uri): void {
        $extension = pathinfo($uri)['extension'];

        $mime = 'text/plain';

        /**
         * If file doesn't exist, return 404 error
         */
        if (!File::exists(__DIR__ . '/../../' . config('app.public') . '/' . $uri)) {
            Container::get(Response::class)->abort(404);

            return;
        }

        $extensionMimeTypes = Mime::TYPES;

        /**
         * Secure vulnerable files.
         */
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

    protected function returnResponse(): void {
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
