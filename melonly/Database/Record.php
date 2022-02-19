<?php

namespace Melonly\Database;

use Melonly\Support\Helpers\Json;
use Stringable;

class Record implements Stringable
{
    public function __toString(): string
    {
        return Json::encode(get_object_vars($this));
    }
}
