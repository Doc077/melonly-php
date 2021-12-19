<?php

namespace Melonly\Bootstrap;

use Throwable;
use Exception;
use Dotenv\Dotenv;
use Melonly\Utilities\Autoloading\Autoloader;
use Melonly\Services\Container;
use Melonly\Routing\Router;
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

            Container::get(Router::class)->evaluate();
        } catch (Throwable $exception) {
            ExceptionHandler::handle($exception);
        }
    }
}
