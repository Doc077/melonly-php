<?php

namespace Melonly\Support\Debug;

use Melonly\Views\View;

class Dumper
{
    public static function dump(...$variables): void
    {
        $count = count($variables);

        View::renderView(__DIR__ . '/../Assets/dump.html', compact(
            'count',
            'variables',
        ), true, forceFruityRender: true);
    }
}
