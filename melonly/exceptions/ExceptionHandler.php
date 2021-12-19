<?php

namespace Melonly\Exceptions;

use Error;
use Exception;
use TypeError;
use PDOException;
use Melonly\Services\Container;
use Melonly\Filesystem\File;
use Melonly\Http\Response;
use Melonly\Http\Url;

class ExceptionHandler {
    public static function handle(Exception | Error | TypeError | PDOException | Notice $exception): void {
        if (env('APP_DEBUG') === 'false') {
            Container::get(Response::class)->abort(500);
        }

        /**
         * Clean output
         */
        if (ob_get_contents()) {
            ob_end_clean();
        }

        $url = rtrim(Url::full(), '/');

        /**
         * Get error file lines count and content.
         */
        $linesCount = 0;

        $linesCount = File::lines($exception->getFile());

        $errorFile = $exception->getFile();

        /**
         * If error occured in a view, replace file to uncompiled template.
         */
        if (strpos($errorFile, 'storage\views') !== false) {
            $errorFile = $GLOBALS['CURRENT_VIEW'];
        }

        $fileContent = file($errorFile);

        include __DIR__ . '/utils/exception-page.php';

        exit;
    }
}

set_error_handler(function (int | string $code, string $message, string $file, int $line) {
    $notice = new Notice($code, $message, $file, $line);

    ExceptionHandler::handle($notice);
});
