<?php

namespace Melonly\Bootstrap;

use Dotenv\Dotenv;
use Melonly\Autoloading\Autoloader;
use Melonly\Authentication\Auth;
use Melonly\Services\Container;
use Melonly\Routing\Router;
use Melonly\Routing\Attributes\Route;
use Melonly\Exceptions\ExceptionHandler;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Throwable;

class Application {
    public static float $performance;

    protected const INCLUDE_FOLDERS = [
        'framework' => [
            'auth',
            'config',
            'container',
            'database',
            'encryption',
            'exceptions',
            'filesystem',
            'helpers',
            'http',
            'mail',
            'routing',
            'testing',
            'translation',
            'utils',
            'validation',
            'vectors',
            'views',
            'websocket'
        ],
        'app' => [
            'controllers',
            'models',
            'routes',
            'tests'
        ]
    ];

    public function __construct() {
        define('PERFORMANCE_START', microtime(true));

        try {
            $this->initializeAndAutoload();

            $this->registerControllers();
            $this->registerModels();

            $this->respondAndTerminate();
        } catch (Throwable $exception) {
            ExceptionHandler::handle($exception);
        }
    }

    protected function initializeAndAutoload(): void {
        require __DIR__ . '/../vendor/autoload.php';
        require __DIR__ . '/../autoloading/Autoloader.php';
        require __DIR__ . '/UnsupportedPHPVersionException.php';

        Dotenv::createImmutable(__DIR__ . '/../..')->load();

        session_start();

        /**
         * Include internal framework files.
         */
        foreach (self::INCLUDE_FOLDERS['framework'] as $folder) {
            Autoloader::loadFiles(__DIR__ . '/../' . $folder);
        }

        Container::initialize();

        if (PHP_VERSION_ID < MELONLY_PHP_MIN_VERSION_ID) {
            throw new UnsupportedPHPVersionException('Melonly requires minimum PHP version ' . MELONLY_PHP_MIN_VERSION . ' or greater');
        }

        /**
         * Include application files.
         */
        foreach (self::INCLUDE_FOLDERS['app'] as $folder) {
            Autoloader::loadFiles(__DIR__ . '/../../' . $folder);
        }

        /**
         * Include composer packages in main directory.
         */
        if (file_exists($file = __DIR__ . '/../../vendor/autoload.php')) {
            require_once $file;
        } elseif (file_exists($file = __DIR__ . '/../../plugins/autoload.php')) {
            require_once $file;
        }

        /**
         * If user is authenticated, save data to Auth.
         */
        if (Auth::logged()) {
            Auth::$userData = $_SESSION['MELONLY_AUTH_USER_DATA'];
        }
    }

    protected function registerControllers(): void {
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
    }

    protected function registerModels(): void {
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
                            ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = [
                                $attribute->getArguments()['type'],
                                'null'
                            ];
                        } else {
                            ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = [
                                $attribute->getArguments()['type']
                            ];
                        }
                    } elseif ($attribute->getName() === \Melonly\Database\Attributes\PrimaryKey::class) {
                        ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = 'id';
                    }
                }
            }
        }
    }

    protected function compressOutput(): void {
        ob_start(function (string $buffer): string {
            $patterns = [
                '/\>[^\S ]+/s',
                '/[^\S ]+\</s',
                '/(\s)+/s'
            ];

            $replacements = ['>', '<', '\\1'];
    
            if (preg_match('/\<html/i', $buffer) === 1 && preg_match('/\<\/html\>/i', $buffer) === 1) {
                $buffer = preg_replace($patterns, $replacements, $buffer);
            }
    
            return str_replace('	', '', $buffer);
        });
    }

    protected function respondAndTerminate(): void {
        if (php_sapi_name() !== 'cli') {
            /**
             * Minify response content if it's not a file request.
             */
            $uri = $_SERVER['REQUEST_URI'];

            if (!array_key_exists('extension', pathinfo($uri)) && env('OUTPUT_COMPRESS') === true) {
                $this->compressOutput();
            }

            define('PERFORMANCE_STOP', microtime(true));

            /**
             * Save the app bootstrap performance result in seconds.
             */
            self::$performance = number_format((PERFORMANCE_STOP - PERFORMANCE_START), 4);

            /**
             * Evaluate routing and generate HTTP response.
             */
            Container::get(Router::class)->evaluate();
        }
    }

    public static function start(): static {
        return new static();
    }
}
