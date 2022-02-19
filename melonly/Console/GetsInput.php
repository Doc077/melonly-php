<?php

namespace Melonly\Console;

use function Termwind\ask;

trait GetsInput
{
    protected function ask(string $data): ?string
    {
        return ask('
            <span class="text-green-400 w-full my-1">' . $data . '</span>
        ');
    }
}
