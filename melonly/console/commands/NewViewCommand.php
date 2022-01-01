<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../views/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'View \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!file_exists(__DIR__ . '/../../views')) {
            mkdir(__DIR__ . '/../../views', 0777, true);
        }

        file_put_contents($fileName, '<!DOCTYPE html>
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

        echo Color::LIGHT_GREEN, 'Created view \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }
};
