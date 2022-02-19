<?php

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../src/Commands/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Command '{$this->arguments[2]}' already exists");

            return;
        }

        $this->publishFileFromTemplate($fileName, 'command', [
            'class' => $this->arguments[2],
        ]);

        $this->infoLine("Created command '{$this->arguments[2]}'");
    }
};
