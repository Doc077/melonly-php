<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;
use Melonly\Views\Engine;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../frontend/views/' . $this->arguments[2] . (config('view.engine') === Engine::Fruity ? '.html' : '.html.twig');

        if (File::exists($fileName)) {
            $this->errorLine("View '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists($folder = __DIR__ . '/../../../views')) {
            File::makeDirectory($folder);
        }

        $this->publishFileFromTemplate($fileName, 'view', [
            'title' => Str::uppercaseFirst($this->arguments[2]),
        ]);

        $this->infoLine("Created view '{$this->arguments[2]}'");
    }
};
