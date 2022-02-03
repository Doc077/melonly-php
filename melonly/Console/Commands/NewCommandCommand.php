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
        if (!Str::endsWith('Command', $this->arguments[2])) {
            $this->arguments[2] .= 'Command';
        }

        $fileName = __DIR__ . '/../../../src/Commands/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Command '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists($folder = __DIR__ . '/../../../src/Commands')) {
            File::makeDirectory($folder);
        }

        $this->publishFileFromTemplate($fileName, 'command', [
            'class' => $this->arguments[2],
        ]);

        $this->infoLine("Created command '{$this->arguments[2]}'");
    }
};
