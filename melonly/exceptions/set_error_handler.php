<?php

namespace Melonly\Exceptions;

set_error_handler(function (int | string $code, string $message, string $file, int $line) {
    $notice = new Notice($code, $message, $file, $line);

    Handler::handle($notice);
});
