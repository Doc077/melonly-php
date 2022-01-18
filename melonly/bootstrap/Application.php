<?php

namespace Melonly\Bootstrap;

use Dotenv\Dotenv;
use Melonly\Autoloading\Autoloader;
use Melonly\Authentication\Auth;
use Melonly\Exceptions\Handler;
use Melonly\Filesystem\File;
use Melonly\GraphQL\GraphQL;
use Melonly\Http\Method as HttpMethod;
use Melonly\Http\Response;
use Melonly\Http\Session;
use Melonly\Services\Container;
use Melonly\Routing\Router;
use Throwable;

class Application {
    public static float $performance;

    protected const AUTOLOAD_FOLDERS = [
        'framework' => [
            'auth',
            'config',
            'container',
            'database',
            'encryption',
            'exceptions',
            'filesystem',
            'graphql',
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

            ClassRegistrar::registerControllers();
            ClassRegistrar::registerModels();

            define('PERFORMANCE_STOP', microtime(true));

            /**
             * Save the app bootstrap performance result in seconds.
             */
            self::$performance = number_format((PERFORMANCE_STOP - PERFORMANCE_START), 4);

            $this->respondAndTerminate();
        } catch (Throwable $exception) {
            Handler::handle($exception);
        }
    }

    protected function initializeAndAutoload(): void {
        require __DIR__ . '/../vendor/autoload.php';
        require __DIR__ . '/../autoloading/Autoloader.php';
        require __DIR__ . '/ClassRegistrar.php';
        require __DIR__ . '/UnsupportedPHPException.php';

        Dotenv::createImmutable(__DIR__ . '/../..')->load();

        /**
         * Include internal framework files.
         */
        foreach (self::AUTOLOAD_FOLDERS['framework'] as $folder) {
            Autoloader::loadFiles(__DIR__ . '/../' . $folder);
        }

        Session::start();
        Container::initialize();

        if (PHP_VERSION_ID < MELONLY_PHP_MIN_VERSION_ID) {
            throw new UnsupportedPHPException('Melonly requires minimum PHP version ' . MELONLY_PHP_MIN_VERSION . ' or greater');
        }

        /**
         * Include application files.
         */
        foreach (self::AUTOLOAD_FOLDERS['app'] as $folder) {
            Autoloader::loadFiles(__DIR__ . '/../../' . $folder);
        }

        /**
         * Include composer packages in main directory.
         */
        if (File::exists($file = __DIR__ . '/../../vendor/autoload.php'))
            require_once $file;
        elseif (File::exists($file = __DIR__ . '/../../plugins/autoload.php'))
            require_once $file;

        /**
         * Check (if exists) or generate security CSRF token.
         */
        if (Session::isSet('MELONLY_CSRF_TOKEN')) {
            if ($_SERVER['REQUEST_METHOD'] === HttpMethod::Post->value && !hash_equals(Session::get('MELONLY_CSRF_TOKEN'), $_POST['csrf_token'])) {
                Container::get(Response::class)->abort(419);
            }
        } else {
            Session::set('MELONLY_CSRF_TOKEN', bin2hex(random_bytes(32)));
        }

        /**
         * If user is authenticated, save data to Auth.
         */
        if (Auth::logged()) {
            Auth::$userData = Session::get('MELONLY_AUTH_USER_DATA');
        }

        /**
         * Initialize GraphQl if enabled.
         */
        if (env('USE_GRAPHQL') === true) {
            GraphQL::initialize();
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
