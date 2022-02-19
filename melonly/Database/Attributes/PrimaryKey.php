<?php

namespace Melonly\Database\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PrimaryKey
{
    public function __construct()
    {
        // 
    }
}
