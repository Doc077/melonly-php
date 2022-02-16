<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('Melon CLI commands:');

        $this->table('
            <thead>
                <tr>
                    <th>Generating files</th>
                    <th align="right">Description</th>
                </tr>
            </thead>

            <tr>
                <td>new:command</td>
                <td align="right">Create custom CLI command</td>
            </tr>

            <tr>
                <td>new:component</td>
                <td align="right">Create new Fruity component</td>
            </tr>

            <tr>
                <td>new:controller</td>
                <td align="right">Create new HTTP controller</td>
            </tr>

            <tr>
                <td>new:exception</td>
                <td align="right">Create new exception class</td>
            </tr>

            <tr>
                <td>new:email</td>
                <td align="right">Create new email class</td>
            </tr>

            <tr>
                <td>new:middleware</td>
                <td align="right">Create new middleware</td>
            </tr>

            <tr>
                <td>new:migration</td>
                <td align="right">Create new database migration</td>
            </tr>

            <tr>
                <td>new:model</td>
                <td align="right">Create new database model</td>
            </tr>

            <tr>
                <td>new:service</td>
                <td align="right">Create new service class</td>
            </tr>

            <tr>
                <td>new:test:feature</td>
                <td align="right">Create new feature test file</td>
            </tr>

            <tr>
                <td>new:test:unit</td>
                <td align="right">Create new unit test file</td>
            </tr>

            <tr>
                <td>new:view</td>
                <td align="right">Create new HTML view template</td>
            </tr>
        ');

        $this->infoLine('');

        $this->table('
            <thead>
                <tr>
                    <th>Other commands</th>
                    <th align="right">Description</th>
                </tr>
            </thead>

            <tr>
                <td>cache:clear</td>
                <td align="right">Clear cache</td>
            </tr>

            <tr>
                <td>command:list</td>
                <td align="right">Get built-in command list</td>
            </tr>

            <tr>
                <td>migrate</td>
                <td align="right">Run database migrations</td>
            </tr>

            <tr>
                <td>scaffold:react</td>
                <td align="right">Create React.js starter project</td>
            </tr>

            <tr>
                <td>scaffold:vue</td>
                <td align="right">Create Vue.js starter project</td>
            </tr>

            <tr>
                <td>server</td>
                <td align="right">Run development server</td>
            </tr>

            <tr>
                <td>test</td>
                <td align="right">Run tests</td>
            </tr>

            <tr>
                <td>test:template</td>
                <td align="right">Create PHPUnit testing structure</td>
            </tr>

            <tr>
                <td>tip</td>
                <td align="right">Get random framework tips</td>
            </tr>

            <tr>
                <td>version</td>
                <td align="right">Get framework version</td>
            </tr>
        ');

        $this->infoLine('(c) ' . MELONLY_AUTHOR);
        $this->infoLine('Enter your command to execute [php melon ...]:');
    }
};
