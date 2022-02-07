<?php

namespace App\Models;

use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\PrimaryKey;
use Melonly\Database\Model;

class User extends Model
{
    #[PrimaryKey]
    public $id;

    #[Column(type: 'string')]
    public $name;

    #[Column(type: 'string', nullable: true)]
    public $email;

    #[Column(type: 'string')]
    public $password;

    #[Column(type: 'datetime', nullable: true)]
    public $created_at;
}
