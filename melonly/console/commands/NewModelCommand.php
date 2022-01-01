<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../models/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Model \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!file_exists(__DIR__ . '/../../../models')) {
            mkdir(__DIR__ . '/../../../models', 0777, true);
        }

        file_put_contents($fileName, '<?php

namespace App\Models;

use Melonly\Database\Model;
use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\PrimaryKey;

class ' . $this->arguments[2] . ' extends Model {
    #[PrimaryKey]
    public $id;

    #[Column(type: \'string\')]
    public $name;
}
');

        echo Color::LIGHT_GREEN, "Created model '{$this->arguments[2]}'", PHP_EOL, Color::RESET;
    }
};
