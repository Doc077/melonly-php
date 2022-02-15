<?php

namespace Melonly\Exceptions;

use Error;
use Exception;
use Melonly\Container\Container;
use Melonly\Filesystem\File;
use Melonly\Http\Response;
use PDOException;
use Melonly\Support\Helpers\Str;
use TypeError;
use Melonly\Views\View;
use Melonly\Views\Engine as ViewEngine;

use function Termwind\{render};

class Handler {
    public static function handle(Exception|Error|TypeError|PDOException|UnhandledError $exception): never {
        if (!config('app.development')) {
            Container::get(Response::class)->abort(500);

            exit();
        }

        self::registerConsoleHandler($exception);

        self::clearTempFiles();

        Container::get(Response::class)->status(500);

        self::renderError($exception);

        exit();
    }

    public static function registerConsoleHandler(Exception|Error|TypeError|PDOException|UnhandledError $exception): void {
        if (php_sapi_name() === 'cli') {
            render('
                <div class="bg-red-400 text-gray-900 px-3 py-1 my-2">
                    <div class="w-full mb-1"><span class="font-bold">Exception:</span> ' . $exception->getMessage() . '</div>
                    <div class="w-full"><span class="font-bold">File:</span> ' . $exception->getFile() . ':' . $exception->getLine() . '</div>
                </div>
            ');

            exit();
        }
    }

    public static function renderError(Exception|Error|TypeError|PDOException|UnhandledError $exception): void {
        $exceptionFile = $exception->getFile();

        /**
         * If exception occured in a view, replace the file with uncompiled template.
         */
        if (Str::contains($exceptionFile, 'storage\temp') && config('view.engine') === ViewEngine::Fruity) {
            $exceptionFile = View::getCurrentView();
        }

        $exceptionType = explode('\\', get_class($exception));

        View::clearBuffer();

        View::renderView(__DIR__ . '/Assets/exception.html', [
            'exception' => $exception,
            'exceptionFile' => $exceptionFile,
            'exceptionType' => end($exceptionType),
            'fileContent' => File::read($exceptionFile),
            'fullExceptionType' => get_class($exception),
            'httpStatus' => 500,
            'linesCount' => File::lines($exception->getFile()),
        ], true, __DIR__ . '/Assets', true);
    }

    public static function clearTempFiles(): void {
        foreach (glob(__DIR__ . '/../../storage/temp/*.php', GLOB_BRACE) as $file) {
            if (is_file($file)) {
                File::delete($file);
            }
        }
    }
}
