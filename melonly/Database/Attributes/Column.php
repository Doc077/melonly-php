<?php

namespace Melonly\Database\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
    public function __construct(string $type, bool $nullable = false)
    {
        // 
    }
}
