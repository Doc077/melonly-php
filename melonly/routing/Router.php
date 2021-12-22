<?php

namespace Melonly\Routing;

use Closure;
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

    public function add(HttpMethod | string $method, string $uri, callable $action, array $data = []): void {
        if ($uri[0] === '/') {
            $uri = substr($uri, 1);
        }
echo 'Added: ' .$uri.'<br>';
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

    public static function get(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Get, $uri, $action, $data);
    }

    public static function post(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Post, $uri, $action, $data);
    }

    public static function put(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Put, $uri, $action, $data);
    }

    public static function patch(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Patch, $uri, $action, $data);
    }

    public static function delete(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Delete, $uri, $action, $data);
    }

    public static function options(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Options, $uri, $action, $data);
    }

    public static function any(string $uri, callable $action, array $data = []): void {
        Container::get(self::class)->add(HttpMethod::Get, $uri, $action, $data);
        Container::get(self::class)->add(HttpMethod::Post, $uri, $action, $data);
        Container::get(self::class)->add(HttpMethod::Put, $uri, $action, $data);
        Container::get(self::class)->add(HttpMethod::Patch, $uri, $action, $data);
        Container::get(self::class)->add(HttpMethod::Delete, $uri, $action, $data);
        Container::get(self::class)->add(HttpMethod::Options, $uri, $action, $data);
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
             * Remove file security vulnerabilities.
             */
            if ($extension === 'php' || $uri === '.htaccess') {
                Container::get(Response::class)->abort(404);

                exit;
            }

            if (array_key_exists(pathinfo($uri)['extension'], $extensionMimeTypes)) {
                $mimeType = $extensionMimeTypes[$extension];
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
                 * Call route action in case of Closure argument.
                 */
                if ($this->actions[$pattern] instanceof Closure) {
                    if (isset($parameters) && isset($parameters[1])) {
                        Container::get(Request::class)->setParameter(explode('?', $parameters[1])[0]);
                    }

                    /**
                     * Inject services to closure.
                     */
                    try {
                        $reflector = new ReflectionFunction($this->actions[$pattern]);
                    } catch (ReflectionException) {
                        throw new Exception('Cannot create instance of service');
                    }

                    $services = [];

                    foreach ($reflector->getParameters() as $param) {
                        $class = $param->getType();

                        $services[] = Container::get($class);
                    }

                    /**
                     * Execute closure from controller.
                     */
                    $this->actions[$pattern](...$services);

                    /**
                     * Render view or show raw response data.
                     */
                    $view = Container::get(Response::class)->getView()[0];
                    $viewVariables = Container::get(Response::class)->getView()[1];

                    if ($view !== null) {
                        $view = str_replace('.', '/', $view);

                        if (!file_exists(__DIR__ . '/../../views/' . $view . '.html')) {
                            throw new Exception("View file '$view' does not exist");
                        }

                        $file = __DIR__ . '/../../views/' . $view . '.html';

                        $GLOBALS['CURRENT_VIEW'] = $file;

                        $compiled = View::compile($file);

                        /**
                         * Get passed variables and include compiled view.
                         */
                        extract($viewVariables);
                        ob_start();

                        include $compiled;

                        /**
                         * Remove temporary file.
                         */
                        unlink($compiled);

                        return;
                    }

                    echo Container::get(Response::class)->getData();
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
