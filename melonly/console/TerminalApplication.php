<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;
use Melonly\Bootstrap\Application;

class TerminalApplication {
    public function __construct() {
        global $argv;

        $this->bootstrap();

        $this->registerDefaultCommand();

        $this->registerFileCreationCommands();

        /**
         * Call the corresponding command function.
         */
        if (file_exists($file = __DIR__ . '/commands/' . ucfirst($argv[1]) . 'Command.php')) {
            $command = require_once $file;

            (new $command())->handle();
        } else {
            echo Color::LIGHT_RED, "Unknown command '{$argv[1]}'", PHP_EOL, Color::RESET;
        }
    }

    protected function bootstrap(): void {
        require_once __DIR__ . '/../bootstrap/Application.php';
        require_once __DIR__ . '/Command.php';

        Application::start();
    }

    protected function registerDefaultCommand(): void {
        global $argv;

        /**
         * If command was not supplied, list all commands.
         */
        if (!isset($argv) || !isset($argv[1]) || empty($argv[1])) {
            echo Color::LIGHT_GREEN, 'Melonly CLI commands:', PHP_EOL, Color::RESET;

            $table = new ConsoleTable();
            
            echo Color::LIGHT_BLUE, PHP_EOL;

            $table->addHeader('Generating files')
                ->addHeader('Other commands')
                ->addRow()
                ->addColumn('new:component')
                ->addColumn('test')
                ->addRow()
                ->addColumn('new:controller')
                ->addColumn('cache')
                ->addRow()
                ->addColumn('new:model')
                ->addColumn('clear')
                ->addRow()
                ->addColumn('new:page')
                ->addColumn('migrate')
                ->addRow()
                ->addColumn('new:table')
                ->addColumn('server')
                ->addRow()
                ->addColumn('new:view')
                ->addColumn('version')
                ->display();
            
            echo Color::RESET, PHP_EOL;

            echo Color::LIGHT_GREEN, 'Enter your command to execute [php melon <command>]:', PHP_EOL, Color::RESET;

            exit;
        }
    }

    protected function registerFileCreationCommands(): void {
        global $argv;

        /**
         * Handle commands with 'new:'.
         */
        if (preg_match('/new:(.*)/', $argv[1], $matches)) {
            $name = 'New' . ucfirst($matches[1]);

            if (file_exists($file = __DIR__ . '/commands/' . $name . 'Command.php')) {
                $command = require_once $file;

                (new $command())->handle();
            } else {
                echo Color::LIGHT_RED, "Unknown command '{$argv[1]}' or cannot create new instance of '{$matches[1]}'", PHP_EOL, Color::RESET;
            }

            exit;
        }
    }

    public static function start(): static {
        return new static();
    }
}
