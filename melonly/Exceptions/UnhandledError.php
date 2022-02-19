<?php

namespace Melonly\Exceptions;

use Stringable;

class UnhandledError implements Stringable
{
    public function getCode(): int|string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function __construct(
        protected int|string $code,
        protected string $message,
        protected string $file,
        protected int $line,
    )
    {
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
