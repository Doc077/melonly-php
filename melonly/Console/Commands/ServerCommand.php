<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $port = 5000;

        if (isset($this->arguments[2]) && (int) $this->arguments[2] !== $port) {
            $port = $this->arguments[2];
        }

        $this->infoBlock("Starting Melonly development server [localhost:$port]");

        shell_exec("php -S 127.0.0.1:$port public/index.php");
    }
};
