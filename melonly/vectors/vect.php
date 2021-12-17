<?php

use Melonly\Support\Containers\Vector;

if (!function_exists('vect')) {
    function vect(...$values): Vector {
        return new Vector(...$values);
    }
}
