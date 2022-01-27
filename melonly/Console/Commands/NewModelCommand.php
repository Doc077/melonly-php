<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../src/Models/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Model '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../src/Models')) {
            mkdir(__DIR__ . '/../../../src/Models', 0777, true);
        }

        File::put($fileName, '<?php

namespace App\Models;

use Melonly\Database\Model;
use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\PrimaryKey;

class ' . $this->arguments[2] . ' extends Model {
    #[PrimaryKey]
    public $id;

    #[Column(type: \'string\')]
    public $name;

    #[Column(type: \'datetime\', nullable: true)]
    public $created_at;
}
');

        $this->infoLine("Created model '{$this->arguments[2]}'");
    }
};
