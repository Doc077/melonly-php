<?php

namespace Melonly\Bootstrap;

use Throwable;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Dotenv\Dotenv;
use Melonly\Utilities\Autoloading\Autoloader;
use Melonly\Services\Container;
use Melonly\Routing\Router;
use Melonly\Routing\Attributes\Route;
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

            if (PHP_VERSION_ID < MELONLY_PHP_MIN_VERSION_ID) {
                throw new Exception('Melonly requires minimum PHP version ' . MELONLY_PHP_MIN_VERSION . ' or higher');
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
                $controllerReflection = new ReflectionClass('\App\Controllers\\' . $class);

                /**
                 * Get all controller public methods.
                 */
                $methods = $controllerReflection->getMethods(ReflectionMethod::IS_PUBLIC);

                foreach ($methods as $method) {
                    $methodReflection = new ReflectionMethod($method->class, $method->name);

                    foreach ($methodReflection->getAttributes() as $attribute) {
                        if ($attribute->getName() === Route::class) {
                            /**
                             * Create new attribute instance.
                             */
                            new Route(...$attribute->getArguments(), class: $method->class);
                        }
                    }
                }
            }

            /**
             * Initialize models with column data types.
             */
            foreach (getNamespaceClasses('App\Models') as $class) {
                $modelReflection = new ReflectionClass('\App\Models\\' . $class);

                /**
                 * Get all model class properties.
                 */
                $properties = $modelReflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

                /**
                 * Assign types to column names.
                 */
                foreach ($properties as $property) {
                    foreach ($property->getAttributes() as $attribute) {
                        if ($attribute->getName() === \Melonly\Database\Attributes\Column::class) {
                            /**
                             * Check whether field is nullable or not.
                             */
                            if (array_key_exists('nullable', $attribute->getArguments()) && $attribute->getArguments()['nullable']) {
                                ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = [$attribute->getArguments()['type'], 'null'];
                            } else {
                                ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = [$attribute->getArguments()['type']];
                            }
                        } elseif ($attribute->getName() === \Melonly\Database\Attributes\PrimaryKey::class) {
                            ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = 'id';
                        }
                    }
                }
            }

            if (php_sapi_name() !== 'cli') {
                /**
                 * Minify response content if it's not a file request.
                 */
                $uri = $_SERVER['REQUEST_URI'];

                if (!array_key_exists('extension', pathinfo($uri)) && env('APP_OUTPUT_COMPRESS') === 'true') {
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
