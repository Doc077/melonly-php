<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Regex;

abstract class Command {
    use DisplaysOutput;

    protected array $arguments = [];

    public function __construct() {
        global $argv;

        foreach ($argv as $argument) {
            $this->arguments[] = $argument;
        }
    }

    protected function publishFileFromTemplate(string $path, string $template, array $arguments = []): void {
        $content = File::content(__DIR__ . '/Assets/' . $template . '.template');

        foreach ($arguments as $variable => $value) {
            $content = Regex::replace('/\{\{ ' . $variable . ' \}\}/', $value, $content);
        }

        File::put($path, $content);
    }

    protected function executeCommand(string $command, array $args = []): void {
        shell_exec("php melon $command " . implode(' ', $args));
    }
}
