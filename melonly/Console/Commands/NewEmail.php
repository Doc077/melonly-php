<?php

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../src/Emails/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Email '{$this->arguments[2]}' already exists");

            return;
        }

        $this->publishFileFromTemplate($fileName, 'email', [
            'class' => $this->arguments[2],
        ]);

        $this->infoLine("Created email '{$this->arguments[2]}'");
    }
};
