<?php

namespace Melonly\Testing;

class Response {
    public function __construct(protected mixed $content, protected int $status) {}

    public function content(): mixed {
        return $this->content;
    }

    public function status(): int {
        return $this->status;
    }
}
