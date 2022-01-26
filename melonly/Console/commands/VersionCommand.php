<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoBlock(MELONLY_VERSION . ' | Released ' . MELONLY_VERSION_RELEASE_DATE);
    }
};