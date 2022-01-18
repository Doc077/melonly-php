<?php

namespace Melonly\Console;

return new class extends Command {
    use DisplaysOutput;

    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('Available Melon CLI commands:');

        $this->table('
            <thead>
                <tr>
                    <th>Generating files</th>
                    <th>Other commands</th>
                </tr>
            </thead>

            <tr>
                <td>new:component</td>
                <td>test</td>
            </tr>

            <tr>
                <td>new:controller</td>
                <td>cache</td>
            </tr>

            <tr>
                <td>new:model</td>
                <td>clear</td>
            </tr>

            <tr>
                <td>new:page</td>
                <td>migrate</td>
            </tr>

            <tr>
                <td>new:table</td>
                <td>server</td>
            </tr>

            <tr>
                <td>new:view</td>
                <td>version</td>
            </tr>
        ');

        $this->infoLine('Enter your command to execute [php melon ...]:');
        $this->infoLine('CLI Documentation: <a class="underline" href="https://melonly.dev/docs/console" target="_blank">Click here</a>');
    }
};
