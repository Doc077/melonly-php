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
                <td>new:feature-test</td>
                <td align="right">Create new feature test file</td>
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
                <td>new:page</td>
                <td align="right">Create new HTML page template</td>
            </tr>

            <tr>
                <td>new:service</td>
                <td align="right">Create new service class</td>
            </tr>

            <tr>
                <td>new:unit-test</td>
                <td align="right">Create new unit test file</td>
            </tr>

            <tr>
                <td>new:view</td>
                <td align="right">Create new view file</td>
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
                <td>cache</td>
                <td align="right">Generate cache for performance optimization</td>
            </tr>

            <tr>
                <td>cache:clear</td>
                <td align="right">Clear cache</td>
            </tr>

            <tr>
                <td>migrate</td>
                <td align="right">Run database migrations</td>
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
