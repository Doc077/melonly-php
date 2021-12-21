<?php

namespace Melonly\Bootstrap;

use Throwable;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use Dotenv\Dotenv;
use Melonly\Utilities\Autoloading\Autoloader;
use Melonly\Services\Container;
use Melonly\Routing\Router;
use Melonly\Routing\Attributes\Route;
use Melonly\Support\Helpers\Str;
use Melonly\Exceptions\ExceptionHandler;

class Application {
    protected const INCLUDE_FOLDERS = [
        'framework' => [
            'auth',
            'config',
            'container',
            'database',
            'exceptions',
            'filesystem',
            'helpers',
            'http',
            'localization',
            'mail',
            'routing',
            'utils',
            'validation',
            'vectors',
            'views',
            'websocket'
        ],
        'app' => [
            'controllers',
            'models',
            'routes'
        ]
    ];

    public function __construct() {
        try {
            require_once __DIR__ . '/../vendor/autoload.php';
            require_once __DIR__ . '/../autoloading/Autoloader.php';

            Dotenv::createImmutable(__DIR__ . '/../..')->load();

            /**
             * Include framework files.
             */
            foreach (self::INCLUDE_FOLDERS['framework'] as $folder) {
                Autoloader::loadAll(__DIR__ . '/../' . $folder);
            }

            Container::initialize();

            $phpVersion = Str::splitAtOccurence('.', 2, phpversion())[0];

            if ($phpVersion < MELONLY_MIN_PHP_VERSION) {
                throw new Exception('Melonly requires PHP version ' . MELONLY_MIN_PHP_VERSION . ' or higher');
            }

            /**
             * Include application files.
             */
            foreach (self::INCLUDE_FOLDERS['app'] as $folder) {
                Autoloader::loadAll(__DIR__ . '/../../' . $folder);
            }

            /**
             * Include composer packages in main directory.
             */
            if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
                require_once __DIR__ . '/../../vendor/autoload.php';
            }
            elseif (file_exists(__DIR__ . '/../../plugins/autoload.php')) {
                require_once __DIR__ . '/../../plugins/autoload.php';
            }

            /**
             * Get all controllers and create attribute instances.
             * Here application will register HTTP routes.
             */
            foreach (getNamespaceClasses('App\Controllers') as $class) {
                $controller = new ReflectionClass('\App\Controllers\\' . $class);

                $methods = $controller->getMethods(ReflectionMethod::IS_PUBLIC);

                foreach ($methods as $method) {
                    $methodReflection = new ReflectionMethod($method->class, $method->name);

                    foreach ($methodReflection->getAttributes() as $attribute) {
                        try {
                            $instance = $attribute->newInstance();
                        } catch (Throwable) {
                            continue;
                        }

                        if (!$instance instanceof Route) {
                            continue;
                        }
                    }
                }
            }

            if (php_sapi_name() !== 'cli') {
                /**
                 * Minify response content if it's not a file request.
                 */
                $uri = $_SERVER['REQUEST_URI'];

                if (
                    !array_key_exists('extension', pathinfo($uri)) &&
                    (bool) env('APP_OUTPUT_COMPRESS')
                ) {
                    ob_start(function (string $buffer): string {
                        $search = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'];
                        $replace = ['>', '<', '\\1'];
                
                        if (preg_match('/\<html/i', $buffer) === 1 && preg_match('/\<\/html\>/i', $buffer) === 1) {
                            $buffer = preg_replace($search, $replace, $buffer);
                        }
                
                        return str_replace('	', '', $buffer);
                    });
                }

                /**
                 * Evaluate routing and generate HTTP response.
                 */
                Container::get(Router::class)->evaluate();
            }
        } catch (Throwable $exception) {
            ExceptionHandler::handle($exception);
        }
    }
}
