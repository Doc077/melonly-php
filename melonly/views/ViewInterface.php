<?php

namespace Melonly\Views;

interface ViewInterface {
    public static function compile(string $file): string;
}
