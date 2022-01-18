<?php

namespace Melonly\Console;

use LucidFrame\Console\ConsoleTable;

return new class extends Command {
    use DisplaysOutput;

    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('Available Melon CLI commands:');

        $table = new ConsoleTable();

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

        $this->infoLine('Enter your command to execute [php melon ...]:');
    }
};
