<?php

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoBlock(MELONLY_VERSION . ' ' . MELONLY_VERSION_STAGE . ' | Released ' . MELONLY_VERSION_RELEASE_DATE);
    }
};
