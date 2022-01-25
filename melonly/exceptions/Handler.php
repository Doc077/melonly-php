<?php

namespace Melonly\Exceptions;

use Error;
use Exception;
use Melonly\Filesystem\File;
use Melonly\Http\Response;
use Melonly\Views\View;
use Melonly\Services\Container;
use Melonly\Support\Helpers\Url;
use PDOException;
use TypeError;

use function Termwind\{render};

class Handler {
    public static function handle(Exception | Error | TypeError | PDOException | Notice $exception): never {
        if (env('APP_DEVELOPMENT') === false) {
            Container::get(Response::class)->abort(500);

            exit();
        }

        /**
         * If CLI mode is enabled, show exception line.
         */
        if (php_sapi_name() === 'cli') {
            render('
                <div class="text-red-400 w-full my-1">Exception: ' . $exception->getMessage() . ' [File: ' . $exception->getFile() . ':' . $exception->getLine() . ']</div>
            ');

            exit();
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

        exit();
    }
}
