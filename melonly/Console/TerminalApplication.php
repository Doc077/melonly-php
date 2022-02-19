<?php

namespace Melonly\Console;

use Melonly\Bootstrap\Application;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;

class TerminalApplication
{
    use DisplaysOutput;

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

        $this->bootstrap();

        /**
         * Handle version variants command.
         */
        if (array_key_exists('v', $this->flags) || array_key_exists('version', $this->flags)) {
            $command = require_once __DIR__ . '/Commands/Version.php';

            (new $command())->handle();

            exit();
        }

        $this->registerDefaultCommand();

        /**
         * Call the corresponding command function.
         */
        if (
            File::exists($file = __DIR__ . '/Commands/' . Str::pascalCase(Str::replace(':', '_', $this->arguments[1])) . '.php') ||
            File::exists($file = __DIR__ . '/../../src/Commands/' . Str::pascalCase(Str::replace(':', '_', $this->arguments[1])) . '.php')
        ) {
            $command = require_once $file;

            (new $command())->handle();
        } else {
            $this->errorLine("Unknown command '{$this->arguments[1]}'");
        }
    }

    protected function bootstrap(): void
    {
        Application::start();
    }

    protected function registerDefaultCommand(): void
    {
        if ($this->isArgumentListEmpty()) {
            $command = require_once __DIR__ . '/Commands/CommandList.php';

            (new $command())->handle();

            exit();
        }
    }

    protected function isArgumentListEmpty(): bool
    {
        return empty($this->arguments) || !isset($this->arguments[1]) || empty($this->arguments[1]);
    }

    public static function start(): static
    {
        return new static();
    }
}
