<?php

namespace Melonly\Exceptions;

set_exception_handler(function (int | string $code, string $message, string $file, int $line) {
    $notice = new Notice($code, $message, $file, $line);

    ExceptionHandler::handle($notice);
});
