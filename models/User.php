<?php

namespace App\Models;

use Melonly\Database\Model;
use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\IncrementingID;

class User extends Model {
    #[IncrementingID]
    public $id;

    #[Column(type: 'string')]
    public $name;

    #[Column(type: 'string', nullable: true)]
    public $email;
}
