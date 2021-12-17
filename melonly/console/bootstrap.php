<?php

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;
use Melonly\Console\Console;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../utils/constants.php';
require_once __DIR__ . '/Console.php';

/**
 * If command not supplied, list all commands.
 */
if (!isset($argv) || !isset($argv[1]) || empty($argv[1])) {
    echo Color::LIGHT_GREEN, 'Melonly CLI commands:', PHP_EOL, Color::RESET;

    $table = new ConsoleTable();
    
    echo Color::LIGHT_BLUE, PHP_EOL;

    $table->addHeader('Generating files')
        ->addHeader('Utility commands')
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

/**
 * Handle commands with 'new:'.
 */
if (preg_match('/new:(.*)/', $argv[1], $matches)) {
    switch ($matches[1]) {
        case 'controller':
        case 'model':
        case 'table':
            Console::{'new' . ucfirst($matches[1])}();
    
            break;
    
        default:
            echo Color::LIGHT_RED, "Unknown command '{$argv[1]}' or cannot create new instance of '{$matches[1]}'", PHP_EOL, Color::RESET;
    }

    exit;
}

/**
 * Call the corresponding command function.
 */
switch ($argv[1]) {
    case 'server':
    case 'version':
        Console::{$argv[1]}();

        break;

    default:
        echo Color::LIGHT_RED, "Unknown command '{$argv[1]}'", PHP_EOL, Color::RESET;
}
