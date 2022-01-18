<?php

namespace Melonly\Console;

use function Termwind\{render};

trait DisplaysOutput {
    protected function errorBlock(string $data): void {
        render('
            <div class="bg-red-400 text-gray-900 px-3 py-1 my-2">' . $data . '</div>
        ');
    }

    protected function errorLine(string $data): void {
        render('
            <div class="text-red-400 w-full my-1">' . $data . '</div>
        ');
    }

    protected function infoBlock(string $data): void {
        render('
            <div class="bg-green-400 text-gray-900 px-3 py-1 my-2">' . $data . '</div>
        ');
    }

    protected function infoLine(string $data): void {
        render('
            <div class="text-green-400 w-full my-1">' . $data . '</div>
        ');
    }
}
