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
                    <th align="right">Other commands</th>
                </tr>
            </thead>

            <tr>
                <td>new:component</td>
                <td align="right">test</td>
            </tr>

            <tr>
                <td>new:controller</td>
                <td align="right">cache</td>
            </tr>

            <tr>
                <td>new:model</td>
                <td align="right">clear</td>
            </tr>

            <tr>
                <td>new:page</td>
                <td align="right">migrate</td>
            </tr>

            <tr>
                <td>new:table</td>
                <td align="right">server</td>
            </tr>

            <tr>
                <td>new:view</td>
                <td align="right">version</td>
            </tr>
        ');

        $this->infoLine('CLI Documentation: <a class="underline" href="https://melonly.dev/docs/console" target="_blank">Click here</a>');
        $this->infoLine('(c) ' . MELONLY_AUTHOR);
        $this->infoLine('Enter your command to execute [php melon ...]:');
    }
};
