<?php

namespace Melonly\Bootstrap;

use Dotenv\Dotenv;
use Melonly\Authentication\Auth;
use Melonly\Exceptions\Handler;
use Melonly\Exceptions\UnhandledError;
use Melonly\Http\Method as HttpMethod;
use Melonly\Http\Response;
use Melonly\Http\Session;
use Melonly\Container\Container;
use Melonly\Routing\Router;
use Throwable;

class Application {
    public static float $performance;

    public function __construct() {
        require_once __DIR__ . '/../../routes/routes.php';

        define('PERFORMANCE_START', microtime(true));

        try {
            $this->initialize();

            ClassRegistrar::registerControllers();
            ClassRegistrar::registerModels();

            define('PERFORMANCE_STOP', microtime(true));

            self::$performance = PERFORMANCE_STOP - PERFORMANCE_START;

            $this->respondAndTerminate();
        } catch (Throwable $exception) {
            Handler::handle($exception);
        }
    }

    protected function registerHandlers(): void {
        set_error_handler(function (
            int | string $code,
            string $message = 'Uncaught error',
            string $file = __DIR__ . '/../../public/index.php',
            int $line = 0,
        ) {
            $error = new UnhandledError($code, $message, $file, $line);
        
            Handler::handle($error);
        });

        set_exception_handler(function (
            int | string $code,
            string $message = 'Uncaught exception',
            string $file = __DIR__ . '/../../public/index.php',
            int $line = 0,
        ) {
            $error = new UnhandledError($code, $message, $file, $line);
        
            Handler::handle($error);
        });
    }

    protected function initialize(): void {
        Dotenv::createImmutable(__DIR__ . '/../..')->load();

        $this->registerHandlers();

        Session::start();
        Container::initialize();

        if (PHP_VERSION_ID < MELONLY_PHP_MIN_VERSION_ID) {
            throw new UnsupportedPHPException('Melonly requires minimum PHP version ' . MELONLY_PHP_MIN_VERSION . ' or greater');
        }

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
    }

    protected function compressOutput(): void {
        ob_start(function (string $buffer): string {
            $patterns = [
                '/\>[^\S ]+/s',
                '/[^\S ]+\</s',
                '/(\s)+/s',
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
