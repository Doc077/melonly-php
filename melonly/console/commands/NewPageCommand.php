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
        $fileName = __DIR__ . '/../../../frontend/views/pages/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Page '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../frontend/views/pages')) {
            mkdir(__DIR__ . '/../../../frontend/views/pages', 0777, true);
        }

        File::put($fileName, '<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>' . Str::uppercaseFirst($this->arguments[2]) . '</title>
    </head>

    <body>
        <main></main>
    </body>
</html>
');

        $this->infoLine("Created page '{$this->arguments[2]}'");
    }
};
