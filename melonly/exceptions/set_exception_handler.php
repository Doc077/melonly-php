<?php

namespace Melonly\Exceptions;

set_exception_handler(function (int | string $code, string $message = 'Uncaught exception', string $file = 'index.php', int $line = 0) {
    $notice = new Notice($code, $message, $file, $line);

    Handler::handle($notice);
});
