<?php

use Melonly\Console\Command;

return new class extends Command {
    protected const GENERATOR_COMMANDS = [
        'new:command' => 'Create custom CLI command',
        'new:component' => 'Create new Fruity component',
        'new:controller' => 'Create new HTTP controller',
        'new:exception' => 'Create new exception class',
        'new:email' => 'Create new email class',
        'new:middleware' => 'Create new middleware',
        'new:migration' => 'Create new database migration',
        'new:model' => 'Create new database model',
        'new:service' => 'Create new service class',
        'new:test:feature' => 'Create new feature test',
        'new:test:unit' => 'Create new unit test',
        'new:view' => 'Create new HTML view template',
    ];

    protected const UTILITY_COMMANDS = [
        'cache:clear' => 'Clear cache',
        'command:list' => 'Get built-in command list',
        'migrate' => 'Run database migrations',
        'scaffold:react' => 'Create React.js starter project',
        'scaffold:vue' => 'Create Vue.js starter project',
        'server' => 'Run development server',
        'test' => 'Run Pest tests',
        'tip' => 'Get random framework tips',
        'version' => 'Get framework version',
    ];

    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('Melon CLI commands:');

        $generators = '';

        foreach (self::GENERATOR_COMMANDS as $command => $description) {
            $generators .= '
                <tr>
                    <td>' . $command . '</td>
                    <td align="right">' . $description . '</td>
                </tr>
            ';
        }

        $this->table('
            <thead>
                <tr>
                    <th>Generating files</th>
                    <th align="right">Description</th>
                </tr>
            </thead>

            ' . $generators . '
        ');

        $this->infoLine('');

        $utils = '';

        foreach (self::UTILITY_COMMANDS as $command => $description) {
            $utils .= '
                <tr>
                    <td>' . $command . '</td>
                    <td align="right">' . $description . '</td>
                </tr>
            ';
        }

        $this->table('
            <thead>
                <tr>
                    <th>Other commands</th>
                    <th align="right">Description</th>
                </tr>
            </thead>

            ' . $utils . '
        ');

        $this->infoLine('Enter your command to execute');
    }
};
