<?php

namespace Melonly\Console;

return new class extends Command {
    use DisplaysOutput;

    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $port = 5000;

        if (isset($this->arguments[2]) && (int) $this->arguments[2] !== $port) {
            $port = $this->arguments[2];
        }

        $this->infoLine("Starting Melonly development server [localhost:$port]");

        shell_exec("php -S 127.0.0.1:$port public/index.php");
    }
};
