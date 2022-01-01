<?php

namespace App\Models;

use Melonly\Database\Model;
use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\IncrementingPrimaryKey;

class Post extends Model {
    #[IncrementingPrimaryKey]
    public $id;

    #[Column(type: 'string')]
    public $name;
}
