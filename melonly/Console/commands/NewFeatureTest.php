<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../tests/feature/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Feature test '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../tests/feature')) {
            mkdir(__DIR__ . '/../../../tests/feature', 0777, true);
        }

        File::put($fileName, '<?php

namespace Tests\Feature;

use Melonly\Testing\TestCase;

class ' . $this->arguments[2] . ' extends TestCase {
    /**
     * Feature test example.
     */
    public function some_feature_test() {
        $variable = true;

        $this->assertTrue($variable);
    }
}
');

        $this->infoLine("Created feature test '{$this->arguments[2]}'");
    }
};
