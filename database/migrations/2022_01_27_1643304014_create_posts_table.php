<?php

namespace Database\Migrations;

use Melonly\Database\Schema;
use Melonly\Database\Table;
use Melonly\Interfaces\Migration;

return new class implements Migration {
    public function setup(): Schema {
        return Schema::createTable('users', function (Table $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamp('created_at');
        });
    }
};
