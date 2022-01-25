<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../tests/unit/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Unit test '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../tests/unit')) {
            mkdir(__DIR__ . '/../../../tests/unit', 0777, true);
        }

        File::put($fileName, '<?php

namespace Tests\Unit;

use Melonly\Testing\TestCase;

class ' . $this->arguments[2] . ' extends TestCase {
    /**
     * Unit test example.
     */
    public function some_unit_test() {
        $variable = true;

        $this->assertTrue($variable);
    }
}
');

        $this->infoLine("Created unit test '{$this->arguments[2]}'");
    }
};
