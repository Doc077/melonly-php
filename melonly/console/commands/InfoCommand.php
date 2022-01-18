<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
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
    }
};
