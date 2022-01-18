<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use Melonly\Bootstrap\Application;

class TerminalApplication {
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
        if (file_exists($file = __DIR__ . '/commands/' . ucfirst($this->arguments[1]) . 'Command.php')) {
            $command = require_once $file;

            (new $command())->handle();
        } else {
            echo Color::LIGHT_RED, "Unknown command '{$this->arguments[1]}'", PHP_EOL, Color::RESET;
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
        if (empty($this->arguments) || !isset($this->arguments[1]) || empty($this->arguments[1])) {
            $command = require_once __DIR__ . '/commands/InfoCommand.php';

            (new $command())->handle();

            exit;
        }
    }

    protected function registerFileCreationCommands(): void {
        /**
         * Handle commands with 'new:'.
         */
        if (preg_match('/new:(.*)/', $this->arguments[1], $matches)) {
            $name = 'New' . ucfirst($matches[1]);

            if (file_exists($file = __DIR__ . '/commands/' . $name . 'Command.php')) {
                $command = require_once $file;

                (new $command())->handle();
            } else {
                echo Color::LIGHT_RED, "Unknown command '{$this->arguments[1]}' or cannot create new instance of '{$matches[1]}'", PHP_EOL, Color::RESET;
            }

            exit;
        }
    }

    public static function start(): static {
        return new static();
    }
}
