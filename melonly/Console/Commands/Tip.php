<?php

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    protected array $tips = [
        [
            'snippet' => 'str-helpers',
            'description' => 'Str class ships with many useful helper methods for casing strings.',
        ],
        [
            'snippet' => 'routing-files',
            'description' => 'When application grows you can divide route definitions into multiple files.',
        ],
        [
            'snippet' => 'custom-commands',
            'description' => 'You can create your own console commands with Melonly.',
        ],
    ];

    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('This tip may be useful:');

        $tip = $this->tips[array_rand($this->tips)];

        $this->codeSnippet(File::content(__DIR__ . '/../Assets/Tips/' . $tip['snippet'] . '.tip'));

        $this->infoLine($tip['description']);
    }
};
