<?php

namespace Database\Migrations;

use Melonly\Database\Schema;
use Melonly\Database\Table;
use Melonly\Interfaces\MigrationInterface;

return new class implements MigrationInterface {
    public function setup(): Schema {
        return Schema::createTable('users', function (Table $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->timestamp('created_at');
        });
    }
};
