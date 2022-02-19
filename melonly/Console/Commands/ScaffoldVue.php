<?php

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->publishFileFromTemplate(__DIR__ . '/../../../frontend/vue/index.js', 'scaffolding.vue');
        $this->publishFileFromTemplate(__DIR__ . '/../../../frontend/vue/components/App.vue', 'scaffolding.vue.app');

        $response = $this->ask('Created Vue.js frontend template. Do you want to install dependencies now? (yes / no)');

        if ($response === 'yes' || $response === null) {
            exec('cd ' . __DIR__ . '/../../.. && npm install -D @babel/core @babel/preset-env babel-loader webpack webpack-cli vue-template-compiler vue vue-loader');
        }
    }
};
