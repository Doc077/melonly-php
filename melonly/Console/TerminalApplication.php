<?php

namespace Melonly\Console;

use Melonly\Bootstrap\Application;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;

class TerminalApplication {
    use DisplaysOutput;

    protected array $arguments = [];

    public function __construct() {
        global $argv;

        foreach ($argv as $argument) {
            $this->arguments[] = $argument;
        }

        $this->bootstrap();

        $this->registerDefaultCommand();

        /**
         * Handle version variants command.
         */
        if ($this->arguments[1] === '-v' || $this->arguments[1] === '--version') {
            $command = require_once __DIR__ . '/Commands/VersionCommand.php';

            (new $command())->handle();

            exit();
        }

        /**
         * Call the corresponding command function.
         */
        if (
            File::exists($file = __DIR__ . '/Commands/' . Str::pascalCase(Str::replace(':', '_', $this->arguments[1])) . 'Command.php') ||
            File::exists($file = __DIR__ . '/../../src/Commands/' . Str::pascalCase(Str::replace(':', '_', $this->arguments[1])) . 'Command.php')
        ) {
            $command = require_once $file;

            (new $command())->handle();
        } else {
            $this->errorLine("Unknown command '{$this->arguments[1]}'");
        }
    }

    protected function bootstrap(): void {
        Application::start();
    }

    protected function registerDefaultCommand(): void {
        if ($this->isArgumentListEmpty()) {
            $command = require_once __DIR__ . '/Commands/InfoCommand.php';

            (new $command())->handle();

            exit();
        }
    }

    protected function isArgumentListEmpty(): bool {
        return empty($this->arguments) || !isset($this->arguments[1]) || empty($this->arguments[1]);
    }

    public static function start(): static {
        return new static();
    }
}
