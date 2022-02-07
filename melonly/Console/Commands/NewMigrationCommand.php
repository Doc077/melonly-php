<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Time;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../database/migrations/' . Time::now()->isoFormat('Y_MM_DD_') . Time::now()->timestamp . '_' . $this->arguments[2] . '.php';

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists($folder = __DIR__ . '/../../../database/migrations')) {
            File::makeDirectory($folder);
        }

        $table = 'table_name';

        if (preg_match('/create_(.*?)_table/', $this->arguments[2], $matches)) {
            $table = $matches[1];
        }

        $this->publishFileFromTemplate($fileName, 'migration', [
            'table' => $table,
        ]);

        $this->infoLine("Created database migration '{$this->arguments[2]}'");
    }
};
