<?php

namespace Melonly\Bootstrap;

use Dotenv\Dotenv;
use Error;
use Exception;
use Melonly\Authentication\Facades\Auth;
use Melonly\Container\Container;
use Melonly\Encryption\Facades\Hash;
use Melonly\Exceptions\Handler;
use Melonly\Exceptions\UnhandledError;
use Melonly\Http\Method as HttpMethod;
use Melonly\Http\Response;
use Melonly\Http\Session;
use Melonly\Support\Debug\Timer;
use Melonly\Support\Helpers\Math;
use Melonly\Routing\Router;
use Throwable;

class Application
{
    public static float $performance;

    public function __construct()
    {
        try {
            $timer = new Timer();

            $this->initialize();

            ClassRegistrar::registerControllers();

            foreach (config('routing.files') as $file) {
                require_once __DIR__ . '/../../routing/' . $file . '.php';
            }

            self::$performance = $timer->stop();

            $this->respondAndTerminate();
        } catch (Throwable $exception) {
            Handler::handle($exception);
        }
    }

    protected function registerHandlers(): void
    {
        error_reporting(-1);

        set_error_handler(function (
            int|Error|Exception $level,
            string $message = 'Uncaught error',
            string $file = '',
            int $line = 0,
        ) {
            if ($level instanceof Error || $level instanceof Exception) {
                throw new Exception($level);
            }

            $error = new UnhandledError($level, $message, $file, $line);

            Handler::handle($error);
        });

        set_exception_handler(function (
            int|Error|Exception $level,
            string $message = 'Uncaught exception',
            string $file = '',
            int $line = 0,
        ) {
            if ($level instanceof Error || $level instanceof Exception) {
                throw new Exception($level);
            }

            $error = new UnhandledError($level, $message, $file, $line);

            Handler::handle($error);
        });
    }

    protected function initialize(): void
    {
        $this->registerHandlers();

        Dotenv::createImmutable(__DIR__ . '/../..')->load();

        Session::start();
        Container::initialize();

        if (PHP_VERSION_ID < MELONLY_PHP_MIN_VERSION_ID) {
            throw new UnsupportedPHPException('Melonly requires minimum PHP version ' . MELONLY_PHP_MIN_VERSION . ' or greater');
        }

        /**
         * Check (if exists) or generate security CSRF token.
         */
        if (Session::isSet('MELONLY_CSRF_TOKEN')) {
            if ($_SERVER['REQUEST_METHOD'] === HttpMethod::Post->value && !Hash::equals(Session::get('MELONLY_CSRF_TOKEN'), $_POST['csrf_token'])) {
                Container::get(Response::class)->abort(419);
            }
        } else {
            Session::set('MELONLY_CSRF_TOKEN', Math::binToHex(random_bytes(32)));
        }

        /**
         * If user is authenticated, save data to Auth.
         */
        if (Auth::logged()) {
            Auth::setUserData(Session::get('MELONLY_AUTH_USER_DATA'));
        }
    }

    protected function compressOutput(): void
    {
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

    protected function respondAndTerminate(): void
    {
        if (php_sapi_name() !== 'cli') {
            /**
             * Minify response content if it's not a file request.
             */
            $uri = $_SERVER['REQUEST_URI'];

            if (!array_key_exists('extension', pathinfo($uri)) && config('app.compress')) {
                $this->compressOutput();
            }

            /**
             * Evaluate routing and generate HTTP response.
             */
            Container::get(Router::class)->evaluate();
        }
    }

    public static function start(): static
    {
        return new static();
    }
}
