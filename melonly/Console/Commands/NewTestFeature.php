<?php

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../tests/Feature/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Feature test '{$this->arguments[2]}' already exists");

            return;
        }

        $this->publishFileFromTemplate($fileName, 'test.feature', [
            'class' => $this->arguments[2],
        ]);

        $this->infoLine("Created feature test '{$this->arguments[2]}'");
    }
};
