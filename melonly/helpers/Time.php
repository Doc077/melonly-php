<?php

namespace Melonly\Support\Helpers;

use Carbon\Carbon;

class Time extends Carbon {
    public static function getDriver(): string {
        return Carbon::class;
    }
}
