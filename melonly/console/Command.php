<?php

namespace Melonly\Console;

abstract class Command {
    protected array $arguments = [];

    public function __construct() {
        global $argv;

        foreach ($argv as $argument) {
            $this->arguments[] = $argument;
        }
    }
}
