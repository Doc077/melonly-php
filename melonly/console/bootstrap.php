<?php

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;
use Melonly\Bootstrap\Application;
use Melonly\Console\Console;

require_once __DIR__ . '/../bootstrap/Application.php';
require_once __DIR__ . '/Console.php';

$app = new Application;

/**
 * If command not supplied, list all commands.
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

$console = new Console();

/**
 * Handle commands with 'new:'.
 */
if (preg_match('/new:(.*)/', $argv[1], $matches)) {
    $method = 'new' . ucfirst($matches[1]);

    if (method_exists($console, $method)) {
        $console->$method();
    } else {
        echo Color::LIGHT_RED, "Unknown command '{$argv[1]}' or cannot create new instance of '{$matches[1]}'", PHP_EOL, Color::RESET;
    }

    exit;
}

/**
 * Call the corresponding command function.
 */
if (method_exists($console, $argv[1])) {
    $console->{$argv[1]}();
} else {
    echo Color::LIGHT_RED, "Unknown command '{$argv[1]}'", PHP_EOL, Color::RESET;
}
