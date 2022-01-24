<?php

namespace Melonly\Console;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('This may be useful for You:');

        $this->codeSnippet('
use Melonly\Support\Helpers\Str;

/* Returns \'MelonlyFramework\' */
Str::pascalCase(\'Melonly framework\');');

    $this->infoLine('Str helper ships with many useful methods. Feel free to use them!');
    }
};
