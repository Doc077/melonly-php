<?php

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->publishFileFromTemplate(__DIR__ . '/../../../frontend/react/index.js', 'scaffolding.react');

        $response = $this->ask('Created React.js frontend template. Do you want to install dependencies now? (yes / no)');

        if ($response === 'yes' || $response === null) {
            exec('cd ' . __DIR__ . '/../../.. && npm install -D @babel/core @babel/preset-env babel-loader webpack webpack-cli react react-dom');
        }
    }
};
