<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../frontend/views/pages/' . $this->arguments[2] . '.html';

        if (File::exists($fileName)) {
            $this->errorLine("Page '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists($folder = __DIR__ . '/../../../frontend/views/pages')) {
            File::makeDirectory($folder);
        }

        $this->publishFileFromTemplate($fileName, 'page', [
            'title' => Str::uppercaseFirst($this->arguments[2]),
        ]);

        $this->infoLine("Created page '{$this->arguments[2]}'");
    }
};
