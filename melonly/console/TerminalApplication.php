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

        $this->registerFileCreationCommands();

        /**
         * Call the corresponding command function.
         */
        if (File::exists($file = __DIR__ . '/commands/' . Str::uppercaseFirst($this->arguments[1]) . 'Command.php')) {
            $command = require_once $file;

            (new $command())->handle();
        } else {
            $this->errorLine("Unknown command '{$this->arguments[1]}'");
        }
    }

    protected function bootstrap(): void {
        require_once __DIR__ . '/../bootstrap/Application.php';
        require_once __DIR__ . '/Command.php';

        Application::start();
    }

    protected function registerDefaultCommand(): void {
        /**
         * If command was not supplied, list all commands.
         */
        if ($this->isArgumentListEmpty()) {
            $command = require_once __DIR__ . '/commands/InfoCommand.php';

            (new $command())->handle();

            exit();
        }
    }

    protected function isArgumentListEmpty(): bool {
        return empty($this->arguments) || !isset($this->arguments[1]) || empty($this->arguments[1]);
    }

    protected function registerFileCreationCommands(): void {
        /**
         * Handle commands with 'new:'.
         */
        if (preg_match('/new:(.*)/', $this->arguments[1], $matches)) {
            $name = 'New' . Str::uppercaseFirst($matches[1]);

            if (File::exists($file = __DIR__ . '/commands/' . Str::pascalCase($name) . 'Command.php')) {
                $command = require_once $file;

                (new $command())->handle();
            } else {
                $this->errorLine("Unknown command '{$this->arguments[1]}' or cannot create new instance of '{$matches[1]}'");
            }

            exit();
        }
    }

    public static function start(): static {
        return new static();
    }
}
