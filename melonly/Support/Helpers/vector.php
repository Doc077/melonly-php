<?php

use Melonly\Support\Containers\Vector;

if (!function_exists('vector')) {
    function vector(...$values): Vector {
        return new Vector(...$values);
    }
}
