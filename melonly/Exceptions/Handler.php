<?php

namespace Melonly\Exceptions;

use Error;
use Exception;
use Melonly\Filesystem\File;
use Melonly\Http\Response;
use Melonly\Views\View;
use Melonly\Container\Container;
use Melonly\Support\Helpers\Url;
use PDOException;
use TypeError;

use function Termwind\{render};

class Handler {
    public static function handle(Exception | Error | TypeError | PDOException | Notice $exception): never {
        if (!env('APP_DEVELOPMENT')) {
            Container::get(Response::class)->abort(500);

            exit();
        }

        /**
         * If CLI mode is enabled, show exception line.
         */
        if (php_sapi_name() === 'cli') {
            render('
                <div class="bg-red-400 text-gray-900 px-3 py-1 my-2">
                    <div class="w-full mb-1"><span class="font-bold">Exception:</span> ' . $exception->getMessage() . '</div>
                    <div class="w-full"><span class="font-bold">File:</span> ' . $exception->getFile() . ':' . $exception->getLine() . '</div>
                </div>
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

        $fileContent = File::read($exceptionFile);

        $exceptionType = explode('\\', get_class($exception));
        $exceptionType = end($exceptionType);

        $fullExceptionType = get_class($exception);

        View::renderView(__DIR__ . '/Assets/exception.html', compact(
            'url',
            'linesCount',
            'exceptionFile',
            'fileContent',
            'exceptionType',
            'fullExceptionType',
        ), true, __DIR__ . '/Assets');

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
