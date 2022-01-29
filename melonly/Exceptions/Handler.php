<?php

namespace Melonly\Exceptions;

use Error;
use Exception;
use Melonly\Container\Container;
use Melonly\Filesystem\File;
use Melonly\Http\Response;
use PDOException;
use Melonly\Support\Helpers\Str;
use Melonly\Support\Helpers\Url;
use TypeError;
use Melonly\Views\View;

use function Termwind\{render};

class Handler {
    public static function handle(Exception|Error|TypeError|PDOException|UnhandledError $exception): never {
        if (!env('APP_DEVELOPMENT')) {
            Container::get(Response::class)->abort(500);

            exit();
        }

        /**
         * If entered in CLI mode show the exception line.
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
        $linesCount = File::lines($exception->getFile());

        $exceptionFile = $exception->getFile();

        /**
         * If exception occured in a view, replace the file with uncompiled template.
         */
        if (Str::contains($exceptionFile, 'storage\temp')) {
            $exceptionFile = View::getCurrentView();
        }

        $fileContent = File::read($exceptionFile);

        $exceptionType = explode('\\', get_class($exception));
        $exceptionType = end($exceptionType);

        $fullExceptionType = get_class($exception);

        View::renderView(__DIR__ . '/Assets/exception.html', compact(
            'exception',
            'exceptionFile',
            'exceptionType',
            'fileContent',
            'fullExceptionType',
            'linesCount',
            'url',
        ), true, __DIR__ . '/Assets');

        /**
         * Delete all compiled temporary templates.
         */
        foreach (glob(__DIR__ . '/../../storage/temp/*.php', GLOB_BRACE) as $file) {
            if (is_file($file)) {
                File::delete($file);
            }
        }

        exit();
    }
}
