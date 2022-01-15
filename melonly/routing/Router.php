<?php

namespace Melonly\Routing;

use Exception;
use ReflectionFunction;
use ReflectionException;
use Melonly\Services\Container;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Http\Mime;
use Melonly\Http\Method as HttpMethod;
use Melonly\Views\View;

class Router implements RouterInterface {
    protected array $patterns = [];

    protected array $methods = [];

    protected array $actions = [];

    protected array $middleware = [];

    protected array $redirects = [];

    public function add(HttpMethod | string $method, string | array $uri, callable $action, array $data = []): void {
        /**
         * Register multiple routes in case of array argument.
         */
        if (is_array($uri)) {
            foreach ($uri as $address) {
                $this->add($method, $address, $action, $data);
            }

            return;
        }

        if ($uri[0] === '/') {
            $uri = substr($uri, 1);
        }

        /**
         * Convert HTTP method enum to string.
         */
        if (!is_string($method)) {
            $method = $method->value;
        } else {
            $method = strtoupper($method);
        }

        /**
         * Create RegExp for dynamic parameters and route.
         */
        $pattern = preg_replace('/:(.*)/', '(.*)', $uri) . '(\\?.*?)?';

        $pattern = '/^' . $method . str_replace('/', '\/', $pattern) . '$/';

        /**
         * Add route data to static arrays.
         */
        $this->patterns[$pattern] = $pattern;
        $this->actions[$pattern] = $action;
    }

    public function get(string | array $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Get, $uri, $action, $data);
    }

    public function post(string | array $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Post, $uri, $action, $data);
    }

    public function put(string | array $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Put, $uri, $action, $data);
    }

    public function patch(string | array $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Patch, $uri, $action, $data);
    }

    public function delete(string | array $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Delete, $uri, $action, $data);
    }

    public function options(string | array $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Options, $uri, $action, $data);
    }

    public function any(string $uri, callable $action, array $data = []): void {
        $this->add(HttpMethod::Get, $uri, $action, $data);
        $this->add(HttpMethod::Post, $uri, $action, $data);
        $this->add(HttpMethod::Put, $uri, $action, $data);
        $this->add(HttpMethod::Patch, $uri, $action, $data);
        $this->add(HttpMethod::Delete, $uri, $action, $data);
        $this->add(HttpMethod::Options, $uri, $action, $data);
    }

    public function evaluate(): void {
        $uri = Container::get(Request::class)->uri();

        if ($uri[0] === '/') {
            $uri = substr($uri, 1);
        }

        /**
         * If URI requests a file, send it and return
         */
        if (array_key_exists('extension', pathinfo($uri)) && pathinfo($uri)['extension']) {
            $extension = pathinfo($uri)['extension'];
            $mimeType = 'text/plain';

            /**
             * If file doesn't exist, return 404 error
             */
            if (!file_exists(__DIR__ . '/../../' . env('APP_PUBLIC') . '/' . $uri)) {
                Container::get(Response::class)->abort(404);

                return;
            }

            $extensionMimeTypes = Mime::TYPES;

            /**
             * Secure vulnerable files.
             */
            if ($extension === 'php' || $uri === '.htaccess') {
                Container::get(Response::class)->abort(404);

                exit;
            }

            if (array_key_exists(pathinfo($uri)['extension'], $extensionMimeTypes)) {
                $mimeType = $extensionMimeTypes[$extension];
            }

            if (pathinfo($uri)['extension'] === 'css') {
                $mimeType = 'text/css';
            }

            header('Content-Type: ' . $mimeType);

            echo readfile(__DIR__ . '/../../' . env('APP_PUBLIC') . '/' . $uri);

            return;
        }

        /**
         * Check if URI matches with one of registered routes.
         */
        $matchesRoute = false;

        foreach ($this->patterns as $pattern) {
            $matchPattern = $_SERVER['REQUEST_METHOD'] . $uri;

            if (preg_match($pattern, $matchPattern, $parameters)) {
                $matchesRoute = true;

                if (array_key_exists($pattern, $this->redirects)) {
                    header('Location: ' . $this->redirects[$pattern]);
                }

                /**
                 * Call route action in case of callable argument.
                 */
                if (is_callable($this->actions[$pattern])) {
                    if (isset($parameters) && isset($parameters[1])) {
                        Container::get(Request::class)->setParameter(explode('?', $parameters[1])[0]);
                    }

                    /**
                     * Inject services to callable.
                     */
                    try {
                        $reflector = new ReflectionFunction($this->actions[$pattern]);
                    } catch (ReflectionException) {
                        throw new Exception('Cannot create instance of a service');
                    }

                    $services = [];

                    foreach ($reflector->getParameters() as $param) {
                        $class = $param->getType();

                        $services[] = Container::get($class);
                    }

                    /**
                     * Execute callable from controller.
                     */
                    $this->actions[$pattern](...$services);

                    /**
                     * Render view or show raw response data.
                     */
                    $view = Container::get(Response::class)->getView()[0];
                    $viewVariables = Container::get(Response::class)->getView()[1];

                    if ($view !== null) {
                        $view = str_replace('.', '/', $view);

                        View::renderView($view, $viewVariables);

                        return;
                    }

                    /**
                     * Set reponse HTTP status code.
                     */
                    http_response_code(Container::get(Response::class)->getStatus());

                    /**
                     * Return response content.
                     * In case of array return JSON.
                     */
                    $responseData = Container::get(Response::class)->getData();

                    if (is_array(Container::get(Response::class)->getData())) {
                        header('Content-Type: application/json');

                        echo json_encode($responseData);
                    } else {
                        echo $responseData;
                    }
                }

                break;
            }
        }

        /**
         * If route has not been found, throw 404 error.
         */
        if (!$matchesRoute) {
            Container::get(Response::class)->abort(404);
        }
    }
}
