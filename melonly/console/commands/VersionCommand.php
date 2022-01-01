<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $table = new ConsoleTable();
    
        echo Color::LIGHT_BLUE, PHP_EOL;
    
        $table->addHeader('Version')
            ->addHeader('Released')
            ->addHeader('Author')
            ->addRow()
            ->addColumn(MELONLY_VERSION . ' STABLE RELEASE')
            ->addColumn('Jan 2022')
            ->addColumn('Dominik Rajkowski (dom.rajkowski@gmail.com)')
            ->display();
        
        echo Color::RESET, PHP_EOL;
    }
};
