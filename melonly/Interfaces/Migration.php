<?php

namespace Melonly\Interfaces;

use Melonly\Database\Schema;

interface Migration {
    public function setup(): Schema;
}
