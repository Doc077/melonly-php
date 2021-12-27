<?php

namespace App\Models;

use Melonly\Database\Model;
use Melonly\Database\Attributes\Column;

class User extends Model {
    #[Column(type: 'id')]
    public $id;

    #[Column(type: 'string')]
    public $name;

    #[Column(type: 'string')]
    public $email;
}
