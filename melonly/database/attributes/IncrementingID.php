<?php

namespace Melonly\Database\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IncrementingID {
    public function __construct() {
        // 
    }
}
