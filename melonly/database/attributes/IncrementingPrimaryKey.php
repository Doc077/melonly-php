<?php

namespace Melonly\Database\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IncrementingPrimaryKey {
    public function __construct() {
        // 
    }
}
