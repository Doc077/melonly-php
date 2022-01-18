<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;

return new class extends Command {
    use DisplaysOutput;

    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../frontend/views/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine('View \'' . $this->arguments[2] . '\' already exists');

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../views')) {
            mkdir(__DIR__ . '/../../../views', 0777, true);
        }

        File::put($fileName, '<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>' . ucfirst($this->arguments[2]) . '</title>
    </head>

    <body>
        <main></main>
    </body>
</html>
');

        $this->infoLine("Created view '{$this->arguments[2]}'");
    }
};
