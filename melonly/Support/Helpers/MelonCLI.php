<?php

namespace Melonly\Support\Console;

class MelonCLI
{
    public static function command(string $command, array $args = []): void
    {
        shell_exec("php melon $command " . implode(' ', $args));
    }
}
