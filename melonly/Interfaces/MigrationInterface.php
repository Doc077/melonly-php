<?php

namespace Melonly\Interfaces;

use Melonly\Database\Schema;

interface MigrationInterface
{
    public function setup(): Schema;
}
