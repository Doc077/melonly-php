<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Regex;

abstract class Command
{
    use DisplaysOutput, GetsInput;

    protected array $arguments = [];

    protected array $flags = [];

    public function __construct()
    {
        global $argv;

        foreach ($argv as $argument) {
            if ($argument[0] === '-') {
                $flag = explode('=', ltrim($argument, '-'));

                $this->flags[$flag[0]] = $flag[1] ?? null;

                continue;
            }

            $this->arguments[] = $argument;
        }
    }

    protected function publishFileFromTemplate(string $path, string $template, array $arguments = []): void
    {
        $content = File::content(__DIR__ . '/Assets/Templates/' . $template . '.template');

        foreach ($arguments as $variable => $value) {
            $content = Regex::replace('/\{\{ ?' . $variable . ' ?\}\}/', $value, $content);
        }

        File::create($path, $content);
    }

    protected function executeCommand(string $command, array $args = []): void
    {
        shell_exec("php melon $command " . implode(' ', $args));
    }
}
