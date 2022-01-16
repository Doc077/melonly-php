<?php

namespace Melonly\Exceptions;

use Error;
use Exception;
use TypeError;
use PDOException;
use Codedungeon\PHPCliColors\Color;
use Melonly\Services\Container;
use Melonly\Filesystem\File;
use Melonly\Http\Response;
use Melonly\Views\View;
use Melonly\Support\Helpers\Url;

class ExceptionHandler {
    public static function handle(Exception | Error | TypeError | PDOException | Notice $exception): never {
        if (env('APP_DEBUG') === false) {
            Container::get(Response::class)->abort(500);
        }

        /**
         * If CLI mode is enabled, show exception line.
         */
        if (php_sapi_name() === 'cli') {
            echo Color::LIGHT_RED, 'Exception: ' . $exception->getMessage() . ' [File: ' . $exception->getFile() . ':' . $exception->getLine() . ']', PHP_EOL, Color::RESET;

            exit;
        }

        View::clearBuffer();

        $url = rtrim(Url::full(), '/');

        /**
         * Get exception file lines count and content.
         */
        $linesCount = 0;

        $linesCount = File::lines($exception->getFile());

        $exceptionFile = $exception->getFile();

        /**
         * If exception occured in a view, replace the file with uncompiled template.
         */
        if (str_contains($exceptionFile, 'storage\views')) {
            $exceptionFile = View::$currentView;
        }

        $fileContent = file($exceptionFile);

        $exceptionType = get_class($exception);

        include __DIR__ . '/utils/exception-page.php';

        /**
         * Delete all compiled temporary templates.
         */
        foreach (glob(__DIR__ . '/../storage/views/*.php', GLOB_BRACE) as $file) {
            if (is_file($file)) {
                File::delete($file);
            }
        }

        exit;
    }
}
