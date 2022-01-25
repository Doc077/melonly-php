<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../frontend/views/components/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Component '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../frontend/views/components')) {
            mkdir(__DIR__ . '/../../../frontend/views/components', 0777, true);
        }

        File::put($fileName, '<div class="' . strtolower($this->arguments[2]) . '">
    {{ $prop }}
</div>
');

        $this->infoLine("Created component '{$this->arguments[2]}'");
    }
};
